<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
      'id',
      'user_id',
      'title',
      'description',
      'remind_at',
      'event_at',
      'created_at',
      'updated_at'
    ];

    public function user(){
      return $this->belongsTo(User::class);
    }
}
