<?php

return [
  App\Providers\AppServiceProvider::class,

  // 🔥 Fortify 本体（絶対必要）
  Laravel\Fortify\FortifyServiceProvider::class,

  // 🔧 カスタム Fortify（あなたが作ったもの）
  App\Providers\FortifyServiceProvider::class,
];
