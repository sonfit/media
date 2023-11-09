<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;


class Domain extends Model
{
    use HasFactory, HasRelationships;


    public function iplist()
    {
        return $this->hasMany(DomainLoginLogs::class,'domain_id');
    }

    public function categories()
    {
        return $this->hasMany(Categories::class,'domain_id');
    }

    public function tags()
    {
        return $this->hasManyDeep(
            Tags::class,
            [Categories::class,CategoryTag::class],
            ['domain_id','category_id','id'],
            ['id','id','tag_id']
        )->distinct();
    }

    //================== WALLPAPERS ============

    public function wallpapers()
    {
        return $this->hasManyDeepFromRelations($this->tags(), (new Tags)->wallpapers())
            ->with(['tags' => function ($query) {
                $tagIds = $this->tags()->pluck('tags.id');
                $query->whereIn('tags.id', $tagIds);
            }])
            ->distinct();
    }

    public function getWallpaper($isBlock){
        return $this->wallpapers()
            ->whereHas('categories', function($query) use ($isBlock) {
                $query->where([
                    ['category_checked_ip', '=', $isBlock],
                    ['domain_id', '=', $this->id]
                ]);
            })
            ->with([
                'categories' => function($query) use ($isBlock) {
                    $query->where([
                        ['category_checked_ip', '=', $isBlock],
                        ['domain_id', '=', $this->id]
                    ]);
                }])
            ->distinct();
    }

    public function ringtones()
    {
        return $this->hasManyDeepFromRelations($this->tags(), (new Tags)->ringtones())->distinct();
    }

    public function musics()
    {
        return $this->hasManyDeepFromRelations($this->tags(), (new Tags)->musics())->distinct();
    }

}
