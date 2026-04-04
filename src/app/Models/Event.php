<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\EventSchedule;

class Event extends Model
{
  use HasFactory;

  protected $fillable = [
    'organization_id',
    'title',
    'display_organization_name',
    'reservation_form_url',
    'description',
    'reservation_start_at',
    'reservation_end_at',
    'flyer_image_1',
    'flyer_image_2',
  ];

  public function organization()
  {
    return $this->belongsTo(Organization::class);
  }

  public function schedules()
  {
    return $this->hasMany(EventSchedule::class);
  }

  public function getDisplayOrganizationName(): string
  {
    return $this->display_organization_name
      ?? $this->organization->name;
  }

  public function orders()
  {
    return $this->hasManyThrough(
      Order::class,
      EventSchedule::class,
      'event_id',
      'event_schedule_id',
      'id',
      'id'
    );
  }

  public function ticketTypes()
  {
    return $this->hasMany(\App\Models\TicketType::class);
  }
}
