@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-events-index.css') }}">
@endsection

@section('content')
<div class="admin-events-wrapper">

  {{-- ページタイトル（カード外） --}}
  <h1 class="page-title">公演管理</h1>

  <div class="admin-events-container">
    @if($events->isEmpty())
      <p class="empty-text">公演はまだ登録されていません。</p>
    @else
      <ul class="event-list">
        @foreach($events as $event)
          <li class="event-item">
            <a href="{{ route('admin.events.show', $event->id) }}">
              {{ $event->title }}
            </a>
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  {{-- 戻るリンク（カード外） --}}
  <div class="back-link-wrapper">
    <a href="{{ route('admin.index') }}" class="back-link">
      ← トップへ戻る
    </a>
  </div>

</div>
@endsection
