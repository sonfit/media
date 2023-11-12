<?php

namespace App\Http\Resources\V4;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicForCategoryResource extends JsonResource
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
            'slider_id' => $this->id,
            'slider_title' => $this->music_title,
            'slider_info' => $this->music_title,
            'songs_ids' => "",
            'slider_image' => $this->music_thumbnail_link ,
        ];
    }
}
