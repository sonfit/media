<?php

namespace App\Http\Resources\V0;

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
        return [
//            'categories' =>
//                CategoriesResource::collection($this->categories),
            'id' => $this->id,
            'name' => $this->wallpaper_name ,
            'thumbnail_image' => asset('storage/wallpapers/thumbnails/'.$this->wallpaper_image),
            'detail_image' => asset('storage/wallpapers/originals/'.$this->wallpaper_image),
            'download_image' => asset('storage/wallpapers/originals/'.$this->wallpaper_image),
            'liked' => $this->liked ?? 0,
            'like_count' => $this->wallpaper_like_count,
            'views' => $this->wallpaper_view_count,
            'feature' => $this->wallpaper_feature,
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
