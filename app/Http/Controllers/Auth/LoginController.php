<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showFormLogin()
    {
        // if (Auth::check()) {
        //     return redirect()->route('admin.login')->with('msg', 'login de');
        // }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $data = [
            'username' => $request->username, 
            'password' => $request->password,
            'level'    => 9,
        ];

        if (!Auth::attempt($data)) {
            return redirect()->route('admin.login')
                    ->with('msg', 'Tên đăng nhập hoặc mật khẩu chưa đúng!');;
        } 

        return redirect()->route('admin.dashboard');
        
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
