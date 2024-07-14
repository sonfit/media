<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainLoginLogs extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function ip_block()
    {
        return $this->belongsTo(IPLIST::class, 'ip_prefix','ip_address');
    }
}
