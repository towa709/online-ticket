<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CancelExpiredTransfers extends Command
{
  protected $signature = 'orders:cancel-expired';
  protected $description = 'Cancel expired transfer orders and restore stock';

  public function handle()
  {
    $expiredOrders = Order::where('status', 'waiting_payment')
      ->whereNotNull('transfer_due_at')
      ->where('transfer_due_at', '<', now())
      ->with(['tickets.ticketType'])
      ->get();

    if ($expiredOrders->isEmpty()) {
      $this->info('No expired orders.');
      return;
    }

    DB::transaction(function () use ($expiredOrders) {

      foreach ($expiredOrders as $order) {

        // 在庫を戻す
        foreach ($order->tickets as $ticket) {
          $ticket->ticketType->increment('stock', $ticket->quantity);
        }

        // ステータス更新
        $order->update([
          'status' => 'cancelled',
        ]);
      }
    });

    $this->info('Expired transfer orders cancelled successfully.');
  }
}
