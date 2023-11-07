<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListType extends Model
{
    use HasFactory;

    public function getIpList(){
        return $this->hasMany(IPLIST::class, 'list_type_id');
    }
}
