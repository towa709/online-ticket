<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('subtitle', 'ことっる・オンラインチケット')</title>
  <link rel="icon" href="{{ asset('images/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>

<body>
  <header class="header">
  <div class="header-left">
  <a href="{{ url('/index') }}" class="header-link">
    <img src="{{ asset('images/logo.png') }}" alt="ことっるロゴ" class="header-logo">
    <span class="header-title">ことっる・オンラインチケット</span>
  </a>
</div>

    @auth('admin')
      <div class="header-right">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout-button">
            ログアウト
          </button>
        </form>
      </div>
    @endauth
  </header>

  <main>
    @yield('content')
  </main>
</body>

</html>
