<?php

namespace App\Http\Resources\V9;

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
            'id' => $this->id,
            'wall_name' => $this->wallpaper_name,
            'category_name' => $this->categories()->first()->category_name,
            'category_id' => $this->categories()->first()->id,
            'image' => $this->wallpaper_image,
            'image_name' => $this->wallpaper_name,
            'download' => $this->wallpaper_download_count,
            'status' => 1,
        ];
    }
}
