<?php

namespace App\Http\Resources\V10;

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
            'id' =>$this->id,
            'slug' =>slug($this->wallpaper_name),
            'title' =>$this->wallpaper_image,
            'description' =>$this->wallpaper_image,
            'main_image_url' => asset('storage/wallpapers/originals/'.$this->wallpaper_image),
            'asset_url' => asset('storage/wallpapers/originals/'.$this->wallpaper_image),
            'thumbnail_url' => asset('storage/wallpapers/thumbnails/'.$this->wallpaper_image),
            'tags' => $tags,
            'category' => $this->categories()->first()->category_name,
            'resolution' =>'',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
