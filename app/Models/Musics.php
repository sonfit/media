<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Musics extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function booted()
    {
        static::deleting(function ($music) {
            $music->tags()->detach();
        });
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tags::class,
            MusicTag::class,
            'music_id',
            'tag_id'
        )->groupBy('tags.id');
    }

    public function categories()
    {
        return $this->hasManyDeep(
            Categories::class,
            [MusicTag::class,CategoryTag::class],
            ['music_id','tag_id','id'],
            ['id','tag_id','category_id']
        )->groupBy('categories.id');
    }

    public function domains()
    {
        return $this->hasManyDeepFromRelations($this->categories(), (new Categories)->domain())->groupBy('domains.id');
    }
}
