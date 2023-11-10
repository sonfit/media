<?php

namespace App\Http\Resources\V9;

use Illuminate\Http\Resources\Json\JsonResource;

class RingtonesCategoriesResource extends JsonResource
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
            'rcid' => $this->id,
            'ringtone_cat_name' => $this->category_name,
            'ringtone_cat_image' => 'domains/'.$this->domain_id.'/categories/'.$this->category_image,
        ];
    }
}
