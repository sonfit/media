<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
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
            'cid' => $this->id,
            'category_name' => $this->category_name,
            'category_image' =>   asset('storage/domains/'.$this->domain_id.'/categories/' . $this->category_image),
            'category_image_thumb' =>  asset('storage/domains/'.$this->domain_id.'/categories/' . $this->category_image),
            'category_total_wall' => $this->wallpapers()->where('image_extension', '<>', 'image/gif')->count(),
        ];
    }
}
