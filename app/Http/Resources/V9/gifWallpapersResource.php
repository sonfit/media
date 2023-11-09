<?php

namespace App\Http\Resources\V9;

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
        return [
            'id' => $this->id,
            'gif_name' => $this->wallpaper_name,
            'gif' => asset('storage/wallpapers/originals/'.$this->wallpaper_image),
            'download' => $this->wallpaper_download_count,
            'status' => 1,
        ];
    }
}
