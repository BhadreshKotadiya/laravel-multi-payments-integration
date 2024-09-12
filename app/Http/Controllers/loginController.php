<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class loginController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->intended(route('product.index'));
        }
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $loginData = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($loginData)) {
            return redirect()->intended(route('product.index'));
        } else {
            return redirect()->route('login');
        }

    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('product.index');
    }
}
