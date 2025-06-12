<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\Cart;
use App\Models\City;
use App\Models\Order;
use App\Models\Address;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\District;
use App\Models\Province;
use App\Models\ProductSize;
use App\Models\SubDistrict;
use App\Models\UserVoucher;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\VoucherHistory;
use Illuminate\Support\Carbon;
use App\Services\XenditService;
use App\Exceptions\OrderException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function getCheckout() {
        $carts = Cart::whereHas('product')->select('id', 'user_id', 'product_id', 'size_id', 'quantity')->with(['product' => function($query) { 
            $query->select('id', 'name', 'color', 'is_preorder', 'price', 'product_code')->with(['first_image']); 
        }, 'size' => function($query) {
            $query->select('id', 'size', 'stock', 'product_id');
        }])
        ->where('user_id', auth()->user()->id)->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart is empty'
            ]);
        }

        $total_price = 0;

        foreach ($carts as $cart) {
            $product = $cart->product;
            $size = $cart->size;

            if ($product->first_image) {
                $product->image = asset('storage/shop/products/' . $product->first_image->image);
                unset($product->first_image);
            }

            // Cek apakah stok mencukupi
            // if ($size && $cart->quantity > $size->stock) {
            //     // Jika stok tidak cukup, hapus item dari keranjang
            //     // $cart->delete();
            //     continue;
            // }

            if ($size && $cart->quantity > $size->stock) {
                // Jika stok tidak cukup, hapus item dari keranjang
                // $cart->delete();
                // jika stok tidak cukup, set ke maksimal stok yang ada
                $cart->quantity = $size->stock;
                $cart->save();
                continue;
            }

            unset($cart->size_id);
            unset($cart->product_id);
            unset($cart->user_id);
            unset($cart->size->product_id);

            $total_price += $product->price * $cart->quantity;
        }

        $address = Address::where('user_id', auth()->user()->id)->where('is_primary', true)->first();

        if ($address) {
            $province = Province::where('prov_id', $address->province_id)->first();
            $city = City::where('city_id', $address->city_id)->first();
            $district = District::where('dis_id', $address->district_id)->first();
            $subdistrict = SubDistrict::where('subdis_id', $address->subdistrict_id)->first();

            // $address->province = $province->prov_name;
            // $address->city = $city->city_name;
            // $address->district = $district->dis_name;
            // $address->subdistrict = $subdistrict->subdis_name;

            $address->province = [
                'id' => $address->province_id,
                'name' => $province->prov_name
            ];
            $address->city = [
                'id' => $address->city_id,
                'name' => $city->city_name
            ];
            $address->district = [
                'id' => $address->district_id,
                'name' => $district->dis_name
            ];
            $address->subdistrict = [
                'id' => $address->subdistrict_id,
                'name' => $subdistrict->subdis_name
            ];
        }

        unset($address->province_id);
        unset($address->city_id);
        unset($address->district_id);
        unset($address->subdistrict_id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'shippings' => [
                    [
                        'id' => 1,
                        'name' => 'PICKUP',
                        'cost' => 0
                    ],
                    [
                        'id' => 2,
                        'name' => 'REGULER',
                        'cost' => 10000
                    ],
                ],
                'address' => $address,
                'carts' => $carts,
                'total_price' => $total_price
            ]
        ]);
    }

    // public function placeOrder(Request $request) {
    //     try {
    //         //cek apakah ada alamat pengiriman
    //         $address = Address::where('user_id', auth()->user()->id)->get();
    //         if (count($address) == 0) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Please add shipping address first'
    //             ]);
    //         }

    //         $address = Address::where('user_id', auth()->user()->id)->where('id', $request->address['id'])->first();
    //          if (!$address) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Shipping address not found'
    //             ]);
    //         }

    //         // -----------------------------------------------

    //         // $shipping

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Order placed successfully',
    //             'tes' => $address,
    //             'data' => $request->all()
    //         ]);
    //     }
    //     catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $th->getMessage()
    //         ]);
    //     }
    // }

    public function placeOrder(Request $request) {
        $user_id = auth()->user()->id;

        try {

            // Validasi struktur request
            $validator = $this->validateRequest($request);

            if ($validator->fails()) {
                return response()->json([
                   'status' => 'error',
                   'message' => $validator->errors()->first()
                ]);
            }

            return DB::transaction(function () use ($request, $user_id) {
                $request_data = $request->all();

                // 1. ALAMAT: Validasi alamat
                $address = $this->validateAddress($user_id, $request_data['address']);

                // 2. METODE PENGIRIMAN: Validasi metode pengiriman
                // $delivery_method = $this->validateDeliveryMethod($request_data['delivery_method'], $address);
                
                // 3. ITEM: Validasi dan proses item-item (dengan lock) termasuk ukuran produk
                $processed_items = $this->validateAndProcessItems($request_data['items']);
                
                // 4. VOUCHER: Validasi dan aplikasikan voucher jika ada
                $voucher_discount = 0;
                $applied_voucher = null;
                if (isset($request_data['voucher']) && !empty($request_data['voucher'])) {
                    $voucher_result = $this->validateAndApplyVoucher($user_id, $request_data['voucher'], $processed_items['subtotal']);
                    
                    $voucher_discount = $voucher_result['discount'];
                    $applied_voucher = $voucher_result['voucher'];
                }

                // Hitung total
                // $delivery_cost = $delivery_method->cost;
                $delivery_cost = $request_data['delivery_method']['cost'];

                $subtotal = $processed_items['subtotal'];
                $total = $subtotal + $delivery_cost - $voucher_discount;

                // Buat order
                $order = new Order();
                $order->order_number = $this->generateOrderNumber();
                $order->user_id = $user_id;
                $order->address_id = $address->id;
                // $order->delivery_method_id = $deliveryMethod->id;
                $order->shipping_type = $request_data['delivery_method']['name'] == 'REGULAR' ? 'THIRD_PARTY' : 'PICKUP';
                $order->shipping_provider = $request_data['delivery_method']['name'] == 'REGULAR' ? 'JNE' : '';

                $order->subtotal = $subtotal;
                $order->shipping_cost = $delivery_cost;
                $order->discount = $voucher_discount;
                $order->total = $total;
                $order->status = 'PENDING';
                if ($applied_voucher) {
                    $order->voucher_id = $applied_voucher->id;
                }
                $order->save();

                // Simpan order items dan reward points
                $total_reward_points = 0;

                // Simpan order items
                foreach ($processed_items['items'] as $item) {
                    $order_item = new OrderProduct();
                    $order_item->order_id = $order->id;
                    $order_item->product_id = $item['product']->id;
                    $order_item->size_id = $item['size']->id;
                    $order_item->quantity = $item['quantity'];
                    $order_item->price = $item['product']->price;
                    $order_item->status = 'PENDING';

                    // Hitung reward points untuk produk ini
                    $reward_points = $this->calculateProductRewardPoints($item['product'], $item['quantity']);
                    $order_item->reward_points = $reward_points;
                    $total_reward_points += $reward_points;

                    $order_item->save();
                }

                // Update voucher usage jika ada
                if ($applied_voucher) {
                    $this->updateVoucherUsage($user_id, $order->id, $applied_voucher);
                }

                $payment = $this->createPayment($user_id, $order, $address, $processed_items['items'], $subtotal, $delivery_cost, $voucher_discount);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Order placed successfully',
                    'data' => [
                        'payment_url' => $payment->invoice_url,
                        'order_number' => $order->order_number,
                        'details' => [
                            'subtotal' => $subtotal,
                            'shipping_cost' => $delivery_cost,
                            'discount' => $voucher_discount,
                            'total' => $total,
                        ]
                    ]
                ]);
            }, 5);

        }
        catch(OrderException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        catch(\Exception $e) {
            Log::error('System error in order placement: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                // 'message' => 'Terjadi kesalahan sistem. Mohon coba beberapa saat lagi.'
                'message' => 'Something went wrong. Please try again later.'
            ]);
            // return response()->json([
            //     'status' => 'error',
            //     'message' => $e->getMessage()
            // ]);
        }
    }

    private function validateRequest($request)
    {
        return Validator::make($request->all(), [
            'address' => 'required',
            'address.id' => 'required|integer',
            'delivery_method' => 'required|array',
            'delivery_method.id' => 'required|integer',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.product' => 'required|array',
            'items.*.product.id' => 'required|integer',
            'items.*.size' => 'required|array',
            'items.*.size.id' => 'required|integer',
            'voucher' => 'nullable',
            'voucher.code' => 'nullable|string'
        ], [
            // 'address.required' => 'Alamat pengiriman harus diisi',
            // 'address.id.required' => 'Alamat pengiriman harus diisi',
            // 'address.id.integer' => 'Alamat pengiriman tidak valid',
            // 'delivery_method.required' => 'Metode pengiriman harus diisi',
            // 'delivery_method.id.required' => 'Metode pengiriman harus diisi',
            // 'delivery_method.id.integer' => 'Metode pengiriman tidak valid',
            // 'items.required' => 'Item harus diisi',
            // 'items.*.id.required' => 'Item harus diisi',
            // 'items.*.id.integer' => 'Item tidak valid',
            // 'items.*.quantity.required' => 'Kuantitas item harus diisi',
            // 'items.*.quantity.integer' => 'Kuantitas item harus berupa angka',
            // 'items.*.quantity.min' => 'Kuantitas item minimal 1',
            // 'items.*.product.required' => 'Produk harus diisi',
            // 'items.*.product.id.required' => 'Produk harus diisi',
            // 'items.*.product.id.integer' => 'Produk tidak valid',
            // 'items.*.size.required' => 'Ukuran harus diisi',
            // 'items.*.size.id.required' => 'Ukuran harus diisi',
            // 'items.*.size.id.integer' => 'Ukuran tidak valid',
            // 'voucher.code.string' => 'Kode voucher tidak valid'
            'address.required' => 'Shipping address is required',
            'address.id.required' => 'Shipping address is required',
            'address.id.integer' => 'Shipping address is invalid',
            'delivery_method.required' => 'Delivery method is required',
            'delivery_method.id.required' => 'Delivery method is required',
            'delivery_method.id.integer' => 'Delivery method is invalid',
            'items.required' => 'Items are required',
            'items.*.id.required' => 'Item is required',
            'items.*.id.integer' => 'Item is invalid',
            'items.*.quantity.required' => 'Quantity is required',
            'items.*.quantity.integer' => 'Quantity must be a number',
            'items.*.quantity.min' => 'Quantity must be at least 1',
            'items.*.product.required' => 'Product is required',
            'items.*.product.id.required' => 'Product is required',
            'items.*.product.id.integer' => 'Product is invalid',
            'items.*.size.required' => 'Size is required',
            'items.*.size.id.required' => 'Size is required',
            'items.*.size.id.integer' => 'Size is invalid',
            'voucher.code.string' => 'Voucher code is invalid'
        ]);
    }

    private function validateAddress($user_id, $address)
    {
        $addresses = Address::where('user_id', auth()->user()->id)->get();
        if (count($addresses) == 0) {
            throw new OrderException('Please add shipping address first');
        }

        $address = $addresses->where('id', $address['id'])->first();
                            
        if (!$address) {
            throw new OrderException('Shipping address not found');
        }
        
        // // Validasi keberadaan relasi
        // if (!$address->province || !$address->city || !$address->district || !$address->subdistrict) {
        //     throw new OrderException('Detail alamat tidak lengkap, mohon lengkapi alamat Anda');
        // }
        
        // // Validasi kode pos
        // if (empty($address->postal_code)) {
        //     throw new OrderException('Kode pos tidak boleh kosong');
        // }
        
        // // Validasi alamat detail
        // if (empty($address->detail_address)) {
        //     throw new OrderException('Detail alamat tidak boleh kosong');
        // }
        
        return $address;
    }

    private function validateDeliveryMethod($delivery_method_data, $address)
    {
        // $delivery_method = DeliveryMethod::find($delivery_method_data['id']);
        
        // if (!$delivery_method) {
        //     throw new OrderException('Metode pengiriman tidak ditemukan');
        // }
        
        // Validasi ketersediaan pengiriman ke area tersebut (contoh)
        // Implementasi ini harus disesuaikan dengan logika bisnis Anda
        // $is_available = $this->checkDeliveryAvailability($delivery_method, $address);
        // if (!$is_available) {
        //     throw new OrderException("Metode pengiriman {$delivery_method->name} tidak tersedia untuk alamat ini");
        // }
        
        // return $delivery_method;
    }
    
    /**
     * Helper untuk cek ketersediaan pengiriman
     */
    private function checkDeliveryAvailability($delivery_method, $address)
    {
        // Implementasi sesuai dengan logika bisnis Anda
        // Misalnya, cek apakah metode pengiriman tersedia untuk provinsi/kota tersebut
        
        // Contoh sederhana: anggap semua metode pengiriman tersedia
        return true;
    }

    private function validateAndProcessItems($request_items)
    {
        $processed_items = [];
        $subtotal = 0;
        
        foreach ($request_items as $request_item) {
            // Lock produk untuk mencegah race condition
            $product = Product::where('id', $request_item['product']['id'])->first();
                           
            if (!$product) {
                // throw new OrderException("Produk dengan ID {$request_item['product']['id']} tidak ditemukan");
                throw new OrderException("Prdouct with ID {$request_item['product']['id']} not found");
            }
            
            // Lock dan validasi ukuran produk
            $product_size = ProductSize::where('id', $request_item['size']['id'])->where('product_id', $product->id)->lockForUpdate()->first();
                                  
            if (!$product_size) {
                // throw new OrderException("Ukuran {$request_item['size']['size']} tidak tersedia untuk produk {$product->name}");
                throw new OrderException("Size {$request_item['size']['size']} not available for product {$product->name}");
            }
            
            // Cek stok berdasarkan ukuran
            if ($product_size->stock < $request_item['quantity']) {
                // throw new OrderException("Stok tidak mencukupi untuk {$product->name} ukuran {$product_size->size}. Tersedia: {$product_size->stock}");
                throw new OrderException("Not enough stock for {$product->name} size {$product_size->size}. Available: {$product_size->stock}");
            }
            
            // Cek jika produk preorder
            if ($product->is_preorder) {
                // Tambahkan logika khusus untuk produk preorder jika diperlukan
            }
            
            // Hitung subtotal item
            $item_subtotal = $product->price * $request_item['quantity'];
            
            // Kurangi stok
            $product_size->stock -= $request_item['quantity'];
            $product_size->save();

            //Hapus produk di cart
            $cart = Cart::where('user_id', auth()->user()->id)->where('product_id', $product->id)->where('size_id', $product_size->id)->first();
            if (!$cart) {
                // throw new OrderException("Produk dengan {$product->name} tidak ditemukan di keranjang");
                throw new OrderException("Product with {$product->name} not found in cart");
            }
            $cart->delete();
            
            $processed_items[] = [
                'product' => $product,
                'size' => $product_size,
                'quantity' => $request_item['quantity'],
                'subtotal' => $item_subtotal
            ];
            
            $subtotal += $item_subtotal;
        }
        
        return [
            'items' => $processed_items,
            'subtotal' => $subtotal
        ];
    }

    private function validateAndApplyVoucher($user_id, $voucher_data, $subtotal)
    {
        $voucher = Voucher::where('code', $voucher_data['code'])->first();
        if (!$voucher) {
            // throw new OrderException('Voucher tidak ditemukan');
            throw new OrderException('Voucher not found');
        }

        // Cek tipe voucher dan batasan penggunaan
        if ($voucher->type == 'NEW_USER') {

            // $user_voucher = UserVoucher::where('user_id', $user_id)->where('voucher_id', $voucher->id)->where('is_redeemed', true)->first();
        
            // if ($user_voucher) {
            //     throw new OrderException('Voucher sudah pernah digunakan');
            // }

            // Voucher personal hanya boleh digunakan oleh pengguna tertentu
            $is_eligible = $this->checkPersonalVoucherEligibility($user_id, $voucher);
            if (!$is_eligible) {
                // throw new OrderException('Voucher ini tidak dapat digunakan oleh Anda');
                // throw new OrderException('Voucher sudah pernah digunakan');
                throw new OrderException('Voucher already used');
            }

            // $valid_until = Carbon::n //duration $voucher->duration;
            $valid_until = Carbon::now()->addDays($voucher->duration);
            $valid_until = Carbon::parse($valid_until->format('Y-m-d'));
        }
        else {
            // Voucher umum dapat digunakan oleh semua pengguna
            $voucher_history = VoucherHistory::where('user_id', $user_id)->where('voucher_id', $voucher->id)->first();
            if ($voucher_history) {
                // throw new OrderException('Voucher sudah pernah digunakan');
                throw new OrderException('Voucher already used');
            }

            $valid_until = Carbon::parse($voucher->expiry_date);
        }
        
        // Cek apakah voucher masih aktif
        if (Carbon::now()->isAfter($valid_until)) {
            // throw new OrderException('Voucher sudah kedaluwarsa');
            throw new OrderException('Voucher expired');
        }
        
        // Cek minimal pembelian
        if ($voucher->minimum_order > 0 && $subtotal < $voucher->minimum_order) {
            // throw new OrderException("Minimal pembelian untuk voucher ini adalah Rp " . number_format($voucher->minimum_order, 0, ',', '.'));
            throw new OrderException("Minimum order for this voucher is Rp ". number_format($voucher->minimum_order, 0, ',', '.'));
        }
        
        // Hitung diskon
        $discount = 0;
        if ($voucher->discount_percentage > 0) {
            if ($voucher->discount_percentage <= 100) {
                // Diskon persentase
                $discount = $subtotal * ($voucher->discount_percentage / 100);
            } else {
                // Diskon nominal
                $discount = $voucher->nominal_discount;
            }
        }
        
        // Pastikan diskon tidak melebihi subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }
        
        return [
            'voucher' => $voucher,
            'discount' => $discount
        ];
    }
    
    /**
     * Helper untuk cek eligibilitas voucher personal
     */
    private function checkPersonalVoucherEligibility($user_id, $voucher)
    {
        // Implementasi sesuai dengan logika bisnis
        // Contoh sederhana: cek apakah voucher ini dialokasikan untuk pengguna ini
        // $user_voucher = DB::table('user_vouchers')
        //                 ->where('user_id', $user_id)
        //                 ->where('voucher_id', $voucher->id)
        //                 ->first();

        $user_voucher = UserVoucher::where('user_id', $user_id)->where('voucher_id', $voucher->id)->where('is_redeemed', true)->first();
        $voucher_history = VoucherHistory::where('user_id', $user_id)->where('voucher_id', $voucher->id)->first();
        
        // return $user_voucher == null;
        return $user_voucher == null && $voucher_history == null;
    }

    /**
     * Menghitung reward points untuk suatu produk
     */
    private function calculateProductRewardPoints($product, $quantity)
    {
        // Implementasi logika perhitungan reward points
        // Contoh implementasi:
        
        // 1. Berdasarkan persentase dari harga produk
        // Misal: 1 point untuk setiap Rp 10.000
        $points_rate = 0.0001; // 0.01% dari harga produk
        $base_points = floor($product->price * $points_rate);
        
        // 2. Cek apakah produk memiliki poin khusus
        // if (isset($product->reward_points) && $product->reward_points > 0) {
        //     $base_points = $product->reward_points;
        // }
        
        // 3. Kalikan dengan quantity
        $total_points = $base_points * $quantity;
        
        // 4. Tambahkan bonus untuk produk tertentu (opsional)
        // if (isset($product->is_special) && $product->is_special) {
        //     $total_points *= 1.5; // bonus 50% untuk produk spesial
        // }
        
        return floor($total_points); // Bulatkan ke bawah
    }
    
    /**
     * Update penggunaan voucher
     */
    private function updateVoucherUsage($user_id, $order_id, $voucher)
    {
        // if ($voucher->type == 'PERSONAL') {
        //     // Update penggunaan voucher personal
        //     DB::table('user_vouchers')
        //         ->where('user_id', $userId)
        //         ->where('voucher_id', $voucher->id)
        //         ->update([
        //             'is_used' => true,
        //             'used_at' => Carbon::now()
        //         ]);
        // }
        
        // Update total penggunaan voucher
        // $voucher->increment('usage_count');

        if ($voucher->type == 'NEW_USER') {
            $user_voucher = UserVoucher::where('user_id', $user_id)->where('voucher_id', $voucher->id)->first();
            if ($user_voucher) {
                $user_voucher->is_redeemed = true;
                $user_voucher->save();
            }
        }

        $voucher_history = new VoucherHistory();
        $voucher_history->user_id = $user_id;
        $voucher_history->voucher_id = $voucher->id;
        $voucher_history->order_id = $order_id;

        $voucher_history->save();

    }

    /**
     * Buat pembayaran
     */
    private function createPayment($user_id, $order, $address, $order_items, $subtotal, $delivery_cost, $voucher_discount)
    {
        $items = [];
        foreach ($order_items as $item) {
            // $order_item = OrderProduct::with(['product:id,name,price', 'size:id,size'])->find($item->id);
            $items[] = [
                'name' => $item['product']['name'],
                'quantity' => (int) $item['quantity'],
                'price' => $item['product']['price'],
                'category' => $item['size']['size'],
            ];
        }

        $data = [
            "external_id" => $order->order_number,
            "customer" => [
                "given_names" => $address->recipient_name,
                // "email" => $address->email,
                "mobile_number" => $address->phone_number,
            ],
            "items" => $items,
            "amount" => $subtotal + $delivery_cost - $voucher_discount,
            "fees" => [
                [
                    "type" => "Shipping Fee",
                    "value" => $delivery_cost,
                ],
                [
                    "type" => "Discount",
                    "value" => -$voucher_discount,
                ]
            ],
            //deskripsion "sudah termasuk biaya kirim dan diskon"
            "description" => 'Includes shipping fee and discount',
            // "success_redirect_url" => '',
        ];

        $xendit = XenditService::createInvoice($data);
        Log::info('Xendit Payment Created Response: '. json_encode($xendit));

        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->user_id = $user_id;
        $payment->external_id = $order->order_number;
        $payment->invoice_id = $xendit->id;
        $payment->amount = $subtotal + $delivery_cost - $voucher_discount;
        $payment->invoice_url = $xendit->invoice_url;
        $payment->status = $xendit->status;
        $payment->expiry_date = date("Y-m-d H:i:s", strtotime($xendit->expiry_date));;
        $payment->callback_data = json_encode($xendit);
        $payment->save();

        return $xendit;
    }
    
    /**
     * Generate nomor order unik
     */
    private function generateOrderNumber()
    {
        $prefix = 'AY';
        $date = Carbon::now()->format('Ymd');
        $randomStr = strtoupper(substr(uniqid(), -4));
        
        return $prefix . $date . $randomStr;
    }
}
