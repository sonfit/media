<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


class Tags extends Model
{
    use HasFactory, HasRelationships;

    public function categories()
    {
        return $this->belongsToMany(
            Categories::class,
            CategoryTag::class,
            'tag_id',
            'category_id'
        );
    }

    public function wallpapers()
    {
        return $this->belongsToMany(
            Wallpapers::class,
            WallpaperTag::class,
            'tag_id',
            'wallpaper_id'
        );
    }

    public function ringtones()
    {
        return $this->belongsToMany(
            Ringtones::class,
            RingtoneTag::class,
            'tag_id',
            'ringtone_id'
        );
    }

    public function musics()
    {
        return $this->belongsToMany(
            Musics::class,
            MusicTag::class,
            'tag_id',
            'music_id'
        );
    }

    public function domains()
    {
        return $this->hasManyDeepFromRelations($this->categories(), (new Categories)->domain());
    }


}
