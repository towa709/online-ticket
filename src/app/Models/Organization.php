<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
  use HasFactory;

  protected $fillable = [
    'admin_id',
    'name',
    'reservation_email',
    'reservation_url',
  ];

  /**
   * 管理者
   */
  public function admin()
  {
    return $this->belongsTo(Admin::class);
  }

  /**
   * 公演一覧
   */
  public function events()
  {
    return $this->hasMany(Event::class);
  }
}
