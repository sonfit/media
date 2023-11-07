<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPLIST extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'list_type_id',
    ];

    public function list_type(){
        return $this->belongsTo(ListType::class, 'list_type_id');
    }

}
