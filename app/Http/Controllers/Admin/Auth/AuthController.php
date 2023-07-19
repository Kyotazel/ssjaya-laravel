<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function login()
    {
        $validatedData = request()->validate([
            'username' => ['required'],
            'password' => ['required']
        ], [
            'username.required' => 'username harus diisi',
            'password.required' => 'password harus diisi',
        ]);

        $admin = Admin::where('username', $validatedData['username'])->first();

        $validatedData['password'] = $validatedData['password'] . '@octacore';

        if (!$admin or hash('sha512', $validatedData['password']) != $admin->password) {
            throw ValidationException::withMessages([
                'username' => ['username & password salah'],
                'password' => ['username & password salah']
            ]);
        }

        Auth::guard('admin')->login($admin);

        return response()->json(['route' => route('admin.dashboard')]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect(route('admin.home'));
    }
}
