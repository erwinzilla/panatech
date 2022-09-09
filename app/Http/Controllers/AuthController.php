<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function showFormLogin()
    {
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $rules = [
            'username'              => 'required|string|min:5',
            'password'              => 'required|string'
        ];

        $messages = [
            'username.required'     => 'Username wajib diisi',
            'username.string'       => 'Username tidak valid',
            'username.min'          => 'Username/Email minimal 5 karakter',
            'password.required'     => 'Password wajib diisi',
            'password.string'       => 'Password harus berupa string'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $data = [
            'username'      => $request->input('username'),
            'password'      => $request->input('password'),
        ];

        $remember = $request->remember == 'on' ? true : false;

        Auth::attempt($data, $remember);

        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            $params = [
                'status'    => 'success',
                'message'   => 'Selamat anda berhasil login'
            ];

//            AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah login', 'user', null);
//            return redirect()->route('home')->with($params);
            return 'success';

        } else { // false
            //Login Fail
            $params = ['Username/Password salah silahkan cek kembali'];
            return redirect()->back()->withErrors($params)->withInput($request->all());
        }

    }
}
