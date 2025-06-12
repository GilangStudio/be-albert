<?php

namespace App\Http\Controllers\API\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request) {
        //{"product_id":3,"quantity":1}

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            // 'quantity' => 'required|integer|min:1',
            'size_id' => 'required|exists:product_sizes,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ]);
        }


        $cart = Cart::where('user_id', auth()->user()->id)
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->first();
        
        $cart_quantity = $cart ? $cart->quantity : 0;

        //cek apakah quantity product tidak melebihi stock product dari size yang dipilih
        $product = Product::find($request->product_id);
        
        $productSize = $product->sizes()->find($request->size_id);
        if ($productSize->stock < $cart_quantity + 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product stock is not enough'
            ]);
        }


        //cart update or create based on user_id and product_id, if product already in cart, update quantity
        Cart::updateOrCreate([
            'user_id' => auth()->user()->id,
            'product_id' => $request->product_id,
            'size_id' => $request->size_id
        ], [
            'quantity' => $cart ? $cart->quantity + 1 : 1
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
            'data' => [
                'total_cart' => Cart::where('user_id', auth()->user()->id)->count()
            ]
        ]);
    }

    public function updateCart(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ]);
        }

        //cek apakah quantity product tidak melebihi stock product dari size yang dipilih
        $cart = Cart::find($request->id);
        $product = Product::find($cart->product_id);

        $productSize = $product->sizes()->find($cart->size_id);

        //if stock is 0, then update quantity to 0
        if ($productSize->stock == 0) {
            $cart->quantity = 0;
            $cart->save();
            return response()->json([
                'status' => 'error',
                'message' => 'Product stock is not enough'
            ]);
        }

        if ($productSize->stock < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product stock is not enough',
            ]);
        }

        $cart = Cart::find($request->id);
        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart updated successfully',
        ]);
    }

    public function getTotalCart() {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_cart' => Cart::where('user_id', auth()->user()->id)->count()
            ]
        ]);
    }

    public function cartGet() {
        $carts = Cart::whereHas('product')->select('id', 'user_id', 'product_id', 'size_id', 'quantity')->with(['product' => function($query) { 
            $query->select('id', 'name', 'slug', 'color', 'is_preorder', 'price', 'product_code', 'slug', 'product_category_id')->with(['first_image']); 
        }, 'size' => function($query) {
            $query->select('id', 'size', 'stock', 'product_id');
        }])
        ->where('user_id', auth()->user()->id)->get();

        $cartProductIds = $carts->pluck('product_id')->toArray();
        $categoryIds = $carts->pluck('product.product_category_id')->unique()->toArray();

        // Get 3 random products from the same categories but not in cart
        $recommendedProducts = Product::select('id', 'name', 'slug', 'color', 'is_preorder', 'price', 'product_code', 'slug', 'product_category_id')->whereIn('product_category_id', $categoryIds)
            ->whereNotIn('id', $cartProductIds)
            ->with(['first_image'])
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function($product) {
                if ($product->first_image) {
                    $product->image = asset('storage/shop/products/' . $product->first_image->image);
                    unset($product->first_image);
                }

                unset($product->product_category_id);
                
                return $product;
            });

        foreach ($carts as $cart) {
            $product = $cart->product;
            $size = $cart->size;

            if ($product->first_image) {
                $product->image = asset('storage/shop/products/' . $product->first_image->image);
                unset($product->first_image);
            }

            // Cek apakah stok mencukupi
            if ($size && $cart->quantity > $size->stock) {
                // Jika stok tidak cukup, hapus item dari keranjang
                // $cart->delete();

                // jika stok tidak cukup, set ke maksimal stok yang ada
                $cart->quantity = $size->stock;
                $cart->save();
                continue;
            }

            if ($cart->quantity == 0) {
                $cart->delete();
                continue;
            }

            unset($cart->size_id);
            unset($cart->product_id);
            unset($cart->user_id);
            unset($cart->size->product_id);
            unset($cart->product->product_category_id);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'carts' => $carts,
                'recommended_products' => $recommendedProducts,
            ]
        ]);
    }

    public function removeCart(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:carts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ]);
        }

        $cart = Cart::find($request->id);
        $cart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart removed successfully',
        ]);
    }
}


// Pastikan cart.quantity adalah string, lalu hanya ambil angka dari input
// const numericValue = String(cart.quantity).replace(/[^0-9]/g, '');  // Hanya angka yang diizinkan
// if (numericValue === '') {
//     cart.quantity = '1'; // Jika kosong atau tidak valid, set ke 1
// } else {
//     cart.quantity = numericValue;
// }
// updateQuantity(cart, cart.quantity);