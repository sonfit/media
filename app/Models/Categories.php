<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Categories extends Model
{
    use HasFactory, HasRelationships;
    protected $guarded=[];


    public function domain(){
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tags::class,
            CategoryTag::class,
            'category_id',
            'tag_id'
        );
    }

    public function wallpapers()
    {
        return $this->hasManyDeep(
            Wallpapers::class,
            [CategoryTag::class, WallpaperTag::class],
            ['category_id','tag_id','id'],
            ['id','tag_id','wallpaper_id']
        )->with(['categories' => function($query) {
            $query->where('categories.id', $this->id);
        }]);
    }

    public function ringtones()
    {
        return $this->hasManyDeep(
            Ringtones::class,
            [CategoryTag::class, RingtoneTag::class],
            ['category_id','tag_id','id'],
            ['id','tag_id','ringtone_id']
        )->with(['categories' => function($query) {
            $query->where('categories.id', $this->id);
        }]);
    }

    public function musics()
    {
        return $this->hasManyDeep(
            Musics::class,
            [CategoryTag::class, MusicTag::class],
            ['category_id','tag_id','id'],
            ['id','tag_id','music_id']
        )->with(['categories' => function($query) {
            $query->where('categories.id', $this->id);
        }]);
    }

}
