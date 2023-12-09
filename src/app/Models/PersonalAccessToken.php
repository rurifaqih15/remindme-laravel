<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class PersonalAccessToken extends Model
{
    const LIFE_TIME=20000;
    protected $fillable =[
        'id',
        'name',
        'tokenable_id',
        'tokenable_type',
        'token',
        'refresh_token',
        'expires_at',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function($m){
            $m->expires_at = Carbon::now()->addSeconds(self::LIFE_TIME);
        });

        self::updating(function($m){
            $m->expires_at = Carbon::now()->addSeconds(self::LIFE_TIME);
        });
    }

    public function tokens()
    {
        return $this->morpTo();
    }
}
