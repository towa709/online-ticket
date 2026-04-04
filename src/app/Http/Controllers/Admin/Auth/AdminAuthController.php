<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
  public function register(AdminRegisterRequest $request)
  {
    Admin::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => Hash::make($request->password),
    ]);

    Auth::guard('admin')->loginUsingId(
      Admin::latest()->first()->id
    );

    return redirect('/index');
  }

  public function login(AdminLoginRequest $request)
  {
    if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
      return back()->withErrors([
        'email' => 'メールアドレスまたはパスワードが違います。',
      ]);
    }

    return redirect('/index');
  }

  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect('/login');
  }
}
