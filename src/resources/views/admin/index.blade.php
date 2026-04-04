@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-index.css') }}">
@endsection

@section('content')
<div class="admin-index-container">
  <h1 class="admin-title">TOP</h1>

  <p class="admin-welcome">
    ようこそ、ことっるチケットオンライントップ
    ページへ。
  </p>

  <div class="admin-menu">
    <a href="{{ route('admin.organization.edit') }}" class="admin-menu-item">
      劇団情報管理
    </a>

    <a href="{{ route('admin.events.index') }}" class="admin-menu-item">
      公演管理
    </a>

    <a href="{{ route('admin.events.create') }}" class="admin-menu-item">
      新規公演登録
    </a>
  </div>
</div>
@endsection
