<?php

namespace App\Http\Resources\V4;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMusicsResource extends JsonResource
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
            'category_id' => $this->id,
            'type' => 'category',
            'category_name' => $this->category_name ,
            'category_image' => $this->category_image ?  asset('storage/domains/'.$this->domain_id.'/categories/'.$this->category_image) : asset('storage/defaultCate.png') ,
        ];
    }
}
