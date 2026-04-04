<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    RateLimiter::for('login', function (Request $request) {
      return Limit::perMinute(5)->by(
        ($request->input('email') ?? 'guest') . '|' . $request->ip()
      );
    });

    RateLimiter::for('two-factor', function (Request $request) {
      return Limit::perMinute(5)->by(
        ($request->session()->get('login.id') ?? 'guest')
      );
    });

    Log::info('AppServiceProvider booted');
  }
}
