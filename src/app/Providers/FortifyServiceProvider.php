<?php

namespace App\Providers;

use App\Actions\Fortify\CreateAdmin;
use App\Http\Requests\AdminLoginRequest;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class FortifyServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    // FormRequest 差し替え
    $this->app->bind(
      LoginRequest::class,
      AdminLoginRequest::class
    );

    Fortify::loginView(fn() => view('admin.auth.login'));
    Fortify::registerView(fn() => view('admin.auth.register'));

    Fortify::createUsersUsing(CreateAdmin::class);

    Fortify::authenticateUsing(function (Request $request) {

      if (
        Auth::guard('admin')->attempt(
          $request->only('email', 'password')
        )
      ) {
        return Auth::guard('admin')->user();
      }

      throw ValidationException::withMessages([
        'login' => 'ログイン情報がありません。',
      ]);
    });

    $this->app->singleton(LogoutResponse::class, function () {
      return new class implements LogoutResponse {
        public function toResponse($request)
        {
          return redirect('/login');
        }
      };
    });
  }
}
