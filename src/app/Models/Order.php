<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'event_schedule_id',
    'name',
    'email',
    'ticket_quantity',
    'payment_method',
    'notes',
    'status',
  ];

  public function schedule()
  {
    return $this->belongsTo(EventSchedule::class, 'event_schedule_id');
  }

  public function tickets()
  {
    return $this->hasMany(OrderTicket::class);
  }
}
