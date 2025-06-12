<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VoucherHistory;

class VoucherController extends Controller
{
    public function getVoucher() {
        $user = auth()->user();
        // $vouchers = UserVoucher::select('id', 'voucher_id')->with('voucher')->where('is_redeemed', false)->where('user_id', auth()->user()->id)->get();
        //ambil voucher yang id nya tidak ada di history voucher
        $vouchers = Voucher::whereDoesntHave('voucherHistories', function ($query) {
            $query->where('user_id', auth()->user()->id);
        // })->where('duration', null)->where('expiry_date', '>', date('Y-m-d'))->orderBy('type', 'desc')->get();
        })->notExpired()->public()->orderBy('type', 'desc')->get();

        $formatted_vouchers = $vouchers->map(function ($voucher) use ($user) {
            // if ($voucher->type === 'NEW_USER' && $voucher->created_at->diffInDays($user->created_at) <= 30) {
            if ($voucher->type === 'NEW_USER') {
                $user_voucher = UserVoucher::where('user_id', $user->id)->whereHas('voucher', function ($query) use ($voucher) {
                    $query->where('id', $voucher->id);
                })->where('is_redeemed', false)->first();

                $voucher_history = VoucherHistory::where('user_id', $user->id)->where('voucher_id', $voucher->id)->first();

                if ($user_voucher && $user_voucher->created_at->diffInDays($user->created_at) <= $voucher->duration && !$voucher_history) {
                    return [
                        'name' => $voucher->name,
                        'code' => $voucher->code,
                        'description' => $voucher->description,
                        'discount' => $voucher->discount_percentage,
                        'minimum_order' => $voucher->minimum_order,
                        'type' => $voucher->type,
                        'valid_until' => $user_voucher->created_at->addDays($voucher->duration)->format('d M Y'),
                    ];
                }
            }

            if ($voucher->type === 'GENERAL') {
                // if ($voucher->expiry_date < date('Y-m-d')) {
                //     return null;
                // }
                // $user_voucher = UserVoucher::where('user_id', $user->id)->where('voucher_id', $voucher->id)->where('is_redeemed', true)->first();

                $voucher_history = VoucherHistory::where('user_id', $user->id)->where('voucher_id', $voucher->id)->first();

                if ($voucher_history) {
                    return null;
                }

                return [
                    'name' => $voucher->name,
                    'code' => $voucher->code,
                    'description' => $voucher->description,
                    'discount' => $voucher->discount_percentage,
                    'minimum_order' => $voucher->minimum_order,
                    'type' => $voucher->type,
                    'valid_until' => date('d M Y', strtotime($voucher->expiry_date)),
                ];
            }
            
            // return [
            //     'name' => $voucher->voucher->name,
            //     'code' => $voucher->voucher->code,
            //     'description' => $voucher->voucher->description,
            //     'discount' => $voucher->voucher->discount_percentage,
            //     'minimum_order' => $voucher->voucher->minimum_order,
            //     'type' => $voucher->voucher->type
            // ];
        });

        //remove null values
        $formatted_vouchers = $formatted_vouchers->filter(function ($voucher) {
            return $voucher !== null;
        });

        $formatted_vouchers = $formatted_vouchers->values();

        // $formatted_vouchers = $formatted_vouchers->sortBy('type');

        return response()->json([
            'status' => 'success',
            'data' => $formatted_vouchers
        ]);
    }

    public function applyVoucher(Request $request) {
        $code = $request->code;

        if (empty($code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher code is required.'
            ]);
        }

        $voucher = Voucher::where('code', $code)->private()->first();
        if (!$voucher) {
            return response()->json([
               'status' => 'error',
               'message' => 'Voucher code is invalid.'
            ]);
        }

        $user = auth()->user();

        // $user_voucher = UserVoucher::where('user_id', $user->id)->where('voucher_id', $voucher->id)->where('is_redeemed', true)->first();
        $voucher_history = VoucherHistory::where('user_id', $user->id)->where('voucher_id', $voucher->id)->first();

        if ($voucher_history) {
            return response()->json([
              'status' => 'error',
              'message' => 'Voucher code already used.'
            ]);
        }

        //check if voucher is expired
        if ($voucher->expiry_date < date('Y-m-d')) {
            return response()->json([
             'status' => 'error',
             'message' => 'Voucher code is expired.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Voucher applied successfully.',
            'data' => [
                'name' => $voucher->name,
                'code' => $voucher->code,
                'description' => $voucher->description,
                'discount' => $voucher->discount_percentage,
                'minimum_order' => $voucher->minimum_order,
                'type' => $voucher->type,
                'valid_until' => date('d M Y', strtotime($voucher->expiry_date))
            ]
        ]);

    }
}
