<?php

namespace App\Http\Resources\V9;

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
            'cat_image' => 'categories/'.$this->category_image,
        ];
    }
}
