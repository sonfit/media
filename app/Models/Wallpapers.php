<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Wallpapers extends Model
{
    use HasFactory, HasRelationships;
    protected $guarded = [];

    public static function booted()
    {
        static::deleting(function ($wallpaper) {
            $pathImage      =   storage_path('app/public/wallpapers/originals/').$wallpaper->wallpaper_image;
            $pathThumbnail  =   storage_path('app/public/wallpapers/thumbnails/').$wallpaper->wallpaper_image;
            try {
                if(file_exists($pathImage)){
                    unlink($pathImage);
                }
                if(file_exists($pathThumbnail)){
                    unlink($pathThumbnail);
                }
            }catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }
            $wallpaper->tags()->detach();
        });
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tags::class,
            WallpaperTag::class,
            'wallpaper_id',
            'tag_id'
        );
    }

    public function categories()
    {
        return $this->hasManyDeep(
            Categories::class,
            [WallpaperTag::class,CategoryTag::class],
            ['wallpaper_id','tag_id','id'],
            ['id','tag_id','category_id']
        )->distinct();
    }

    public function domains()
    {
        return $this->hasManyDeepFromRelations($this->categories(), (new Categories)->domain());
    }
}
