<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configure extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'email_configuration' => 'object',
    ];


    protected static function boot ()
    {
        parent::boot();
        static::saved(function (){
            Cache::forget('ConfigureSetting');
        });

    }
}
