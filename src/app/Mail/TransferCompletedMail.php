<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferCompletedMail extends Mailable
{
  use SerializesModels;

  public function __construct(
    public Order $order
  ) {}

  public function build()
  {
    return $this
      ->subject('【入金確認】ご予約が確定しました')
      ->view('emails.transfer-completed');
  }
}
