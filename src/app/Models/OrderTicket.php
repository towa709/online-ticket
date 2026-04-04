<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTicket extends Model
{
  protected $fillable = [
    'order_id',
    'ticket_type_id',
    'quantity',
  ];

  public function ticketType()
  {
    return $this->belongsTo(TicketType::class);
  }
}
