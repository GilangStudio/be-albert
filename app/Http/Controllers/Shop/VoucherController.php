<?php

namespace App\Http\Controllers\Shop;

use App\Models\User;
use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::all();
        return view('pages.shop.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.shop.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|unique:vouchers,code|string|max:20',
            // 'type' => 'required|in:GENERAL,NEW_USER',
            'discount' => 'nullable|numeric|min:1|max:100',
            'min_order' => 'required|numeric',
            'expired_date' => 'nullable|date_format:d/m/Y',
            'voucher_status' => 'nullable|in:1',
        ]));

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        //expired date must be in the future
        if ($request->has('expired_date')) {
            $expiredDate = Carbon::createFromFormat('d/m/Y', $request->expired_date);
            if ($expiredDate->isPast()) {
                return redirect()->back()->with('error', 'Expired date must be in the future')->withInput();
            }
        }

        // $existingVoucher = Voucher::where('code', $request->code)->first();
        // if ($existingVoucher) {
        //     return redirect()->back()->with('error', 'Voucher code already exists')->withInput();
        // }

        $voucher = Voucher::create([
            'name' => $request->name,
            'description' => $request->description,
            'code' => $request->code,
            'discount_percentage' => $request->discount,
            'minimum_order' => $request->min_order,
            'type' => 'GENERAL',
            'expiry_date' => Carbon::createFromFormat('d/m/Y', $request->expired_date)->format('Y-m-d'),
            'is_public' => $request->voucher_status == 1 ? 1 : 0,
        ]);
    
        // Jika voucher adalah NEW_USER, berikan kepada pengguna baru yang memenuhi syarat
        // if ($voucher->type === 'NEW_USER') {
        //     $this->assignVoucherToNewUsers($voucher);
        // }

        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        return view('pages.shop.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $validator = Validator::make($request->all(), ([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:20',
            'discount' => 'nullable|numeric|min:1|max:100',
            'min_order' => 'required|numeric',
            'expired_date' => 'nullable|date_format:d/m/Y',
            'voucher_status' => 'nullable|in:1',
        ]));

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        //expired date must be in the future
        if ($request->has('expired_date')) {
            $expiredDate = Carbon::createFromFormat('d/m/Y', $request->expired_date);
            if ($expiredDate->isPast()) {
                return redirect()->back()->with('error', 'Expired date must be in the future')->withInput();
            }
        }

        $existingVoucher = Voucher::where('code', $request->code)->where('id', '!=', $voucher->id)->first();
        if ($existingVoucher) {
            return redirect()->back()->with('error', 'Voucher code already exists')->withInput();
        }

        $voucher->name = $request->name;
        $voucher->code = $request->code;
        $voucher->description = $request->description;
        $voucher->discount_percentage = $request->discount;
        $voucher->minimum_order = $request->min_order;
        $voucher->expiry_date = Carbon::createFromFormat('d/m/Y', $request->expired_date)->format('Y-m-d');
        $voucher->is_public = $request->voucher_status == 1 ? 1 : 0;
        $voucher->save();

        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully.');
    }

    public function vouchers_special() {
        $new_user_voucher = Voucher::where('type', 'NEW_USER')->first();

        return view('pages.shop.vouchers.special', compact('new_user_voucher'));
    }

    public function vouchers_special_update(Request $request) {
        $validator = Validator::make($request->all(), ([
            'code' => 'required|string|max:20',
            'discount' => 'nullable|numeric|min:1|max:100',
            'min_order' => 'required|numeric',
            'duration' => 'required|numeric',
        ]));

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $request->code = str_replace(' ', '', $request->code);

        //update or create voucher with type = NEW_USER
        $voucher = Voucher::updateOrCreate([
            'type' => 'NEW_USER'
        ], [
            'name' => 'New User Voucher',
            'code' => $request->code,
            'discount_percentage' => $request->discount,
            'minimum_order' => $request->min_order,
            'duration' => $request->duration,
        ]);

        // $this->assignVoucherToNewUsers($voucher);

        return redirect()->route('vouchers.index')->with('success', 'Voucher new user updated successfully.');
    }


    // Fungsi untuk memberikan voucher kepada pengguna baru yang belum mendapatkannya
    private function assignVoucherToNewUsers(Voucher $voucher)
    {
        $newUsers = User::where('is_admin', 0)->whereDoesntHave('vouchers', function ($query) use ($voucher) {
            $query->where('voucher_id', $voucher->id);
        })
        // ->where('created_at', '>=', now()->subDays(7)) // Cek user yang mendaftar dalam 7 hari terakhir
        ->get();

        foreach ($newUsers as $user) {
            UserVoucher::create([
                'user_id' => $user->id,
                'voucher_id' => $voucher->id,
                'is_redeemed' => false,
            ]);
        }
    }
}
