<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $guarded = ['id'];



    protected $appends =['flag'];

    public function getFlagAttribute()
    {
        return getFile(config('location.country.path').$this->image);
    }

}
