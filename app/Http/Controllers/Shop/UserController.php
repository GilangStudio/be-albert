<?php

namespace App\Http\Controllers\Shop;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserRegisteredEvent;
use App\Http\Controllers\Controller;
use App\Notifications\UserRegistered;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('is_admin', 0)->latest()->get();
        return view('pages.shop.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.shop.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // "name" => "Leonard Morgan"
        // "country_code" => "62"
        // "phone" => "088584940979"
        // "email" => "sonas@example.com"
        // "password" => "Pa$$w0rd!"
        // "confirm_password" => "Pa$$w0rd!"

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|in:62',
            'phone' => 'required|string|unique:users,phone_number|min:8|max:15',
            'email' => 'required|email|unique:users',
            'gender' => 'required|string|in:MALE,FEMALE',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }
        
        if (substr($request->phone, 0, 1) === '0' && $request->country_code === '62') {
            $phone = substr($request->phone, 1);
        } else {
            $phone = $request->phone;
        }

        $user = User::create([
            'name' => $request->name,
            'country_code' => $request->country_code,
            'phone_number' => $phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'gender' => $request->gender
        ]);

        $user->notify(new UserRegistered($user));

        event(new UserRegisteredEvent($user));

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('pages.shop.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|in:62',
            'phone' => 'required|string|unique:users,phone_number,' . $user->id . '|min:8|max:15',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required|string|in:MALE,FEMALE',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        if ($request->password) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first())->withInput();
            }

            $user->password = bcrypt($request->password);
        }

        if (substr($request->phone, 0, 1) === '0' && $request->country_code === '62') {
            $phone = substr($request->phone, 1);
        } else {
            $phone = $request->phone;
        }

        $user->name = $request->name;
        $user->country_code = $request->country_code;
        $user->phone_number = $phone;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
