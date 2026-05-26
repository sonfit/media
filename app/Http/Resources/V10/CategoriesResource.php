<?php

namespace App\Http\Resources\V10;

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
            'name_slug' => $this->category_name,
            'display_name'=>$this->category_name,
        ];
    }
}
