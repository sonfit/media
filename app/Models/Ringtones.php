<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Ringtones extends Model
{

    use HasFactory, HasRelationships;
    protected $guarded = [];

    public static function booted()
    {
        static::deleting(function ($ringtone) {
            $pathRingtone      =   storage_path('app/public/ringtones/').$ringtone->ringtone_file;
            try {
                if(file_exists($pathRingtone)){
                    unlink($pathRingtone);
                }
            }catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }
            $ringtone->tags()->detach();
        });
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tags::class,
            RingtoneTag::class,
            'ringtone_id',
            'tag_id'
        );
    }

    public function categories()
    {
        return $this->hasManyDeep(
            Categories::class,
            [RingtoneTag::class,CategoryTag::class],
            ['ringtone_id','tag_id','id'],
            ['id','tag_id','category_id']
        );
    }

    public function domains()
    {
        return $this->hasManyDeepFromRelations($this->categories(), (new Categories)->domain());
    }
}
