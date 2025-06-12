<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\City;
use App\Models\Order;
use App\Models\District;
use App\Models\Province;
use App\Models\SubDistrict;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function getOrders()
    {
        $orders = Order::with(['payment:id,order_id,invoice_url', 'address', 'voucher' => function ($query) { 
            $query->select('id', 'code', 'discount_percentage');
        }, 'order_products' => function ($query) {
            $query->select('order_id', 'product_id', 'size_id', 'quantity')->productDetail();
        }])->where('user_id', auth()->user()->id)->latest()->get();

        foreach ($orders as $key => $order) {
            
            $order->invoice_url = $order->payment?->invoice_url;
            unset($order->payment);

            $province = Province::where('prov_id', $order->address->province_id)->first();
            $city = City::where('city_id', $order->address->city_id)->first();
            $district = District::where('dis_id', $order->address->district_id)->first();
            $subdistrict = SubDistrict::where('subdis_id', $order->address->subdistrict_id)->first();

           $order->address->province = [
                'id' => $order->address->province_id,
                'name' => $province->prov_name
            ];
            $order->address->city = [
                'id' => $order->address->city_id,
                'name' => $city->city_name
            ];
            $order->address->district = [
                'id' => $order->address->district_id,
                'name' => $district->dis_name
            ];
            $order->address->subdistrict = [
                'id' => $order->address->subdistrict_id,
                'name' => $subdistrict->subdis_name
            ];

            // unset($order->address->province_id);
            // unset($order->address->city_id);
            // unset($order->address->district_id);
            // unset($order->address->subdistrict_id);
        }

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }
}