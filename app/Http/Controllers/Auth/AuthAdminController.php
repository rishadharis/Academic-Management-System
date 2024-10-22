<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthAdminController extends Controller
{
    public function index()
    {
        return view("auth.login_admin");
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->orWhere('email', $request->username)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                return redirect()->route('dashboard.admin');
            } else {
                return back()->with('message', 'Username/Password Salah.');
            }
        }

        return back()->with('message', 'Account Tidak Terdaftar.');
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect()->route('login.admin');
    }
}
