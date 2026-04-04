<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventReservationController;
use App\Http\Controllers\Admin\EventScheduleController;
use App\Http\Controllers\Admin\TicketTypeController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\ReservationController as PublicReservationController;

/*
|--------------------------------------------------------------------------
| 管理者トップ（ログイン後TOP）
|--------------------------------------------------------------------------
*/

Route::middleware('admin')->group(function () {
  Route::get('/index', function () {
    return view('admin.index');
  })->name('admin.index');
});

/*
|--------------------------------------------------------------------------
| 管理者機能（認証必須）
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')
  ->prefix('admin')
  ->name('admin.')
  ->group(function () {

    /*
    | 団体情報
    */
    Route::get('/organization', [OrganizationController::class, 'edit'])
      ->name('organization.edit');
    Route::post('/organization', [OrganizationController::class, 'update'])
      ->name('organization.update');

    /*
    | 公演一覧
    */
    Route::get('/events', [EventController::class, 'index'])
      ->name('events.index');

    /*
    | 新規公演
    */
    Route::get('/events/create', [EventController::class, 'create'])
      ->name('events.create');
    Route::post('/events', [EventController::class, 'store'])
      ->name('events.store');

    /*
    | 公演管理TOP
    */
    Route::get('/events/{event}', [EventController::class, 'show'])
      ->name('events.show');

    /*
    | 公演基本情報編集
    */
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])
      ->name('events.edit');
    Route::post('/events/{event}', [EventController::class, 'update'])
      ->name('events.update');

    /*
    | 予約フォーム設定
    */
    Route::get(
      '/events/{event}/reservation/create',
      [EventReservationController::class, 'create']
    )->name('events.reservation.create');

    Route::post(
      '/events/{event}/reservation',
      [EventReservationController::class, 'store']
    )->name('events.reservation.store');

    Route::get(
      '/events/{event}/reservation/preview',
      [EventReservationController::class, 'preview']
    )->name('events.reservation.preview');

    /*
    | 予約一覧
    */
    Route::get(
      '/events/{event}/reservations',
      [EventReservationController::class, 'index']
    )->name('events.reservations.index');

    /*
    | 予約キャンセル
    */
    Route::patch(
      '/reservations/{order}/cancel',
      [ReservationController::class, 'cancel']
    )->name('reservations.cancel');

    /*
    | CSVエクスポート
    */
    Route::get(
      '/events/{event}/reservations/export',
      [ReservationController::class, 'exportCsv']
    )->name('events.reservations.export');

    Route::get(
      '/reservations/{order}/edit',
      [ReservationController::class, 'edit']
    )->name('reservations.edit');

    Route::patch(
      '/reservations/{order}',
      [ReservationController::class, 'update']
    )->name('reservations.update');
    /*
    |--------------------------------------------------------------------------
    | 公演日程管理
    |--------------------------------------------------------------------------
    */
    Route::prefix('events/{event}')
      ->name('events.')
      ->group(function () {

        Route::get('/schedules', [EventScheduleController::class, 'index'])
          ->name('schedules.index');

        Route::post('/schedules', [EventScheduleController::class, 'store'])
          ->name('schedules.store');

        Route::delete('/schedules/{schedule}', [EventScheduleController::class, 'destroy'])
          ->name('schedules.destroy');

        Route::put('/schedules/{schedule}', [EventScheduleController::class, 'update'])
          ->name('schedules.update');
      });

    /*
    |--------------------------------------------------------------------------
    | 券種管理
    |--------------------------------------------------------------------------
    */
    Route::prefix('events/{event}')
      ->name('events.')
      ->group(function () {

        Route::get('/ticket-types', [TicketTypeController::class, 'index'])
          ->name('ticket-types.index');

        Route::post('/ticket-types', [TicketTypeController::class, 'store'])
          ->name('ticket-types.store');
      });

    Route::get(
      '/events/{event}/reservations/waiting',
      [ReservationController::class, 'waitingList']
    )->name('events.reservations.waiting');

    Route::patch(
      '/reservations/{order}/confirm',
      [ReservationController::class, 'confirmPayment']
    )->name('reservations.confirm');
  });
/*
|--------------------------------------------------------------------------
| 公開予約フォーム
|--------------------------------------------------------------------------
*/
Route::post(
  '/events/{event}/reservations',
  [PublicReservationController::class, 'store']
)->name('reservations.store');

Route::get(
  '/events/{event}/reservations/complete',
  function (\App\Models\Event $event) {
    return view('reservations.complete', compact('event'));
  }
)->name('reservations.complete');

Route::get(
  '/events/{event}/reservations',
  [PublicReservationController::class, 'create']
)->name('reservations.create');

Route::get(
  '/events/{event}/reservations/{order}/transfer',
  function (\App\Models\Event $event, \App\Models\Order $order) {
    return view('reservations.transfer', compact('event', 'order'));
  }
)->name('reservations.transfer');
