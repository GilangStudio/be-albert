<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class XenditIncomingRequest extends Controller
{
    public function invoicesStatus(Request $request) {
        $x_callback_token = $request->header('X-Callback-Token');

        if ($x_callback_token != '8Ng1FVCEe0aosXjhP97EZ2WwbRBC0CL3r7VAo6vHGKo0ycer') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Token'
            ], 400);
        }

        if ($request->isMethod('get')) {
            return response()->json([
                'success' => true,
            ]);
        }
        else {
            $incoming_request = json_decode(file_get_contents('php://input'));

            if ($incoming_request->status === 'PAID' || $incoming_request->status === 'SETTLED') {

                $payment = Payment::where('external_id', $incoming_request->external_id)->first();
                
                if ($payment) {
                    $payment->update([
                        'payment_id' => $incoming_request->payment_method == 'CREDIT_CARD' ? $incoming_request->credit_card_charge_id : $incoming_request->payment_id,
                        'payment_method' => $incoming_request->payment_method,
                        'payment_channel' => $incoming_request->payment_channel,
                        'status' => $incoming_request->status,
                        'payment_date' => Carbon::now(),
                    ]);

                    OrderProduct::where('order_id', $payment->order_id)->update([
                       'status' => 'PAID',
                    ]);

                    Order::where('id', $payment->order_id)->update([
                        'status' => 'PAID',
                    ]);
                }

            }
            else if ($incoming_request->status === 'EXPIRED') {
                $payment = Payment::where('external_id', $incoming_request->external_id)->first();

                if ($payment) {
                    $payment->update([
                        'status' => $incoming_request->status,
                    ]);

                    $order = Order::where('id', $payment->order_id)->first();
                    if ($order) {
                        $order->update([
                            'status' => 'CANCELED',
                        ]);
                    }

                    $orderProducts = OrderProduct::where('order_id', $payment->order_id)->get();
                    foreach ($orderProducts as $orderProduct) {
                        $orderProduct->update([
                           'status' => 'CANCELED',
                        ]);

                        $size = ProductSize::where('id', $orderProduct->size_id)->first();
                        if ($size) {
                            $size->update([
                               'stock' => $size->stock + $orderProduct->quantity,
                            ]);
                        }
                    }

                }
            }

            return response()->json([
                'success' => true,
            ]);
            
        }
    }

    // public function paymentExpired(Request $request) {
    //     $x_callback_token = $request->header('X-Callback-Token');

    //     if ($x_callback_token != '8Ng1FVCEe0aosXjhP97EZ2WwbRBC0CL3r7VAo6vHGKo0ycer') {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid Token'
    //         ], 400);
    //     }

    //     if ($request->isMethod('get')) {
    //         return response()->json([
    //             'success' => true,
    //         ]);
    //     }
    //     else{
    //         return response()->json([
    //             'success' => true,
    //             'data' => $request->all(),
    //         ]);
    //     }
    // }
}
