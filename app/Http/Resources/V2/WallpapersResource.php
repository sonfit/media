<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class WallpapersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tags = [];
        foreach ($this->tags as $tag){
            $tags[] = $tag->tag_name;
        }

        return [
            'num' => 12,
            'id' => $this->id,
            'cat_id' => '',
            'wallpaper_type' => $this->wallpaper_type,
            'wallpaper_image' => asset('storage/wallpapers/originals/'.$this->wallpaper_image),
            'wallpaper_image_thumb' => asset('storage/wallpapers/thumbnails/'.$this->wallpaper_image),
            'total_views' => $this->wallpaper_view_count,
            'total_rate' => $this->wallpaper_like_count,
            'rate_avg' => $this->wallpaper_download_count,
            'total_download' => $this->wallpaper_download_count,
            'is_favorite' => false,
            'wall_colors' => 1,
            'resolution' => 'n/a',
            'size' => 'n/a',
            'wall_tags' => implode(",", $tags),
            'category_name' => $this->wallpaper_name,
            'category_image' => '',
            'category_image_thumb' => '',
        ];
    }
}
