<?php

namespace App\Http\Controllers\Sales\Auth;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index()
    {
        return view('sales.login');
    }

    public function login()
    {
        $validatedData = request()->validate([
            'username' => ['required'],
            'password' => ['required']
        ],[
            'username.required' => 'username harus diisi',
            'password.required' => 'password harus diisi',
        ]);

        $sales = Sales::where('id_sales',$validatedData['username'])->first();

        if(!$sales or md5($validatedData['password']) != $sales->password) {
            throw ValidationException::withMessages([
                'username' => ['username & password salah'],
                'password' => ['username & password salah']
            ]);
        }

        Auth::guard('sales')->login($sales);

        return response()->json(['route' => route('sales.dashboard')]);

    }

    public function logout()
    {
        Auth::guard('sales')->logout();

        return redirect(route('sales.home'));
    }
}
