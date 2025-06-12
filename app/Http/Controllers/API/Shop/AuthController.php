<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\UserRegisteredEvent;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserRegistered;
use App\Notifications\UserForgotPassword;
use App\Services\EncryptionService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');
    //     $remember = $request->has('remember');

    //     if (Auth::attempt($credentials, $remember)) {
    //         /** @var \App\Models\User $user **/
    //         $user = Auth::user();
    //         // $token = $user->createToken('YourAppName')->plainTextToken;
    //         $token = $user->createToken(config('app.name'))->accessToken;

    //         $user = [
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'country_code' => $user->country_code,
    //             'phone_number' => $user->phone_number,
    //             'points' => $user->points()
    //         ];

    //         return response()->json([
    //             'status' => 'success', 
    //             'message' => 'Logged in successfully',
    //             'data' => [
    //                 'token' => $token,
    //                 'user' => $user
    //             ]
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'Invalid credentials',
    //     ]);
    // }

    public function login(Request $request)
    {
        // Ambil input email/nomor telepon dan password
        $input = $request->only('email_or_phone', 'password');
        $remember = $request->has('remember');

        // Tentukan apakah input adalah email atau nomor telepon
        $user = null;
        if (filter_var($input['email_or_phone'], FILTER_VALIDATE_EMAIL)) {
            // Jika input adalah email, cari user berdasarkan email
            $user = User::where('email', $input['email_or_phone'])->first();
        } else {
            //remove 0 from phone number if it starts with 0
            if (substr($input['email_or_phone'], 0, 1) === '0') {
                $input['email_or_phone'] = substr($input['email_or_phone'], 1);
            }
            if (substr($input['email_or_phone'], 0, 2) === '62') {
                $input['email_or_phone'] = substr($input['email_or_phone'], 2);
            }
            // Jika input bukan email, asumsikan itu nomor telepon
            $user = User::where('phone_number', $input['email_or_phone'])->first();
        }

        // Jika user ditemukan dan password cocok, lanjutkan login
        if ($user && Hash::check($input['password'], $user->password)) {
            // Login berhasil
            Auth::login($user, $remember);

            // Generate token jika diperlukan
            $token = $user->createToken(config('app.name'))->accessToken;

            // Persiapkan data user untuk dikembalikan
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'country_code' => $user->country_code,
                'phone_number' => $user->phone_number,
                'points' => $user->points(),
                'shop_summary' => [
                    'total_carts' => $user->cart()->count(),
                    'total_active_orders' => $user->ordersActive()->count(),
                    'total_wishlists' => $user->wishlists()->count()
                ]
            ];

            return response()->json([
                'status' => 'success', 
                'message' => 'Logged in successfully',
                'data' => [
                    'token' => $token,
                    'user' => $userData,
                ]
            ]);
        }

        // Jika user tidak ditemukan atau password tidak cocok
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials or user not found',
        ]);
    }


    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|in:62',
            'phone_number' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        if (substr($request->phone_number, 0, 1) === '0') {
            $request->phone_number = substr($request->phone_number, 1);
        }

        if (substr($request->phone_number, 0, 2) === '62') {
            $request->phone_number = substr($request->phone_number, 2);
        }

        DB::beginTransaction(); // Mulai transaksi

        try {
            // Proses pembuatan user
            $user = User::create([
                'name' => $request->name,
                'country_code' => $request->country_code,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            // Generate token
            $token = $user->createToken(config('app.name'))->accessToken;

            // Kirim notifikasi ke user
            $user->notify(new UserRegistered($user));

            // Trigger event (misalnya untuk integrasi lainnya)
            event(new UserRegisteredEvent($user));

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Registered successfully',
                'data' => [
                    'token' => $token,
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollback();

            // Log error untuk referensi debugging
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Kembalikan response error ke user
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed. Please try again.',
            ]);
        }
    }

    public function reset_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Check rate limiting - 1 request per 5 minutes
        $key = 'password_reset_' . ($input['email'] ?? request()->ip());
        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'status' => 'error',
                'message' => 'Too many password reset attempts. Please try again in ' . $seconds . ' seconds.'
            ], 200);
        }

        RateLimiter::hit($key, 300); // 300 seconds = 5 minutes

        // Cari user berdasarkan email atau nomor telepon
        // $user = User::where('email', $input['email_or_phone'])
        //     ->orWhere('phone_number', $input['email_or_phone'])
        //     ->first();

        $user = null;
        // if (filter_var($input['email_or_phone'], FILTER_VALIDATE_EMAIL)) {
            // Jika input adalah email, cari user berdasarkan email
            $user = User::where('email', $request->email)->first();
        // } else {
        //     //remove 0 from phone number if it starts with 0
        //     if (substr($input['email_or_phone'], 0, 1) === '0') {
        //         $input['email_or_phone'] = substr($input['email_or_phone'], 1);
        //     }
        //     if (substr($input['email_or_phone'], 0, 2) === '62') {
        //         $input['email_or_phone'] = substr($input['email_or_phone'], 2);
        //     }
        //     // Jika input bukan email, asumsikan itu nomor telepon
        //     $user = User::where('phone_number', $input['email_or_phone'])->first();
        // }

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ]);
        }

        // Generate token reset password
        $token = EncryptionService::encrypt($user->id . '|' . now()->addMinutes(30)->timestamp);
        $user->reset_password_token = $token;
        $user->save();
        // Kirim notifikasi ke user
        $user->notify(new UserForgotPassword($user));

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset link sent successfully',
        ]);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }

    public function account()
    {
        $user = User::find(Auth::user()->id);

        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'gender' => $user->gender,
            'birth_date' => $user->birth_date,
            'country_code' => $user->country_code,
            'phone_number' => $user->phone_number,
            'points' => $user->points(),
            'shop_summary' => [
                'total_carts' => $user->cart()->count(),
                'total_active_orders' => $user->ordersActive()->count(),
                'total_wishlists' => $user->wishlists()->count()
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function accountUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'country_code' => 'required|string|in:62',
            'phone_number' => 'required|string|unique:users,phone_number,' . Auth::user()->id,
            'birth_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'gender' => 'required|string|in:MALE,FEMALE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->birth_date = $request->birth_date;
        $user->gender = $request->gender;
        $user->country_code = $request->country_code;
        $user->phone_number = $request->phone_number;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Account updated successfully',
        ]);
    }

    public function accountPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully',
        ]);
    }

    public function change_password(Request $request, $token) {
        // Validasi input (password, password_confirmation)
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Cari token
        $user = User::where('reset_password_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid token'
            ]);
        }

        // Update password
        $user->password = bcrypt($request->password);
        $user->reset_password_token = null;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }

    public function deleteAccount() {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Account deleted successfully',
        ]);
    }

    public function validate_token(Request $request) {
        try {
            $token = $request->token;
            $user = User::where('reset_password_token', $token)->first();

            $token = EncryptionService::decrypt($token);

            // Extract user ID and expiration timestamp from decrypted token
            $token_parts = explode('|', $token);
            if (count($token_parts) !== 2) {
                return response()->json([
                    'status' => 'error',
                    // 'message' => 'Invalid token format'
                ]);
            }

            // $userId = $token_parts[0];
            $expirationTimestamp = $token_parts[1];

            // Check if token has expired
            if (now()->timestamp > $expirationTimestamp) {
                return response()->json([
                    'status' => 'error',
                    // 'message' => 'Token has expired'
                ]);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
            ]);
        }

        if ($user) {
            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }
}
