<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class gifWallpapersResource extends JsonResource
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
            'num' => 10,
            'id' => $this->id,
            'gif_image' => asset('storage/wallpapers/originals/'.$this->wallpaper_image),
            'gif_tags' => implode(",", $tags),
            'total_views' => $this->wallpaper_view_count,
            'total_rate' => $this->wallpaper_like_count,
            'rate_avg' => $this->wallpaper_download_count,
            'is_favorite' => false
        ];
    }
}
