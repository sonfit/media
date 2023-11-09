<?php

namespace App\Http\Resources\V9;

use Illuminate\Http\Resources\Json\JsonResource;

class RingtonesResource extends JsonResource
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
            'rid' => $this->id,
            'r_cat' => $this->categories()->first()->id,
            'ringtone_cat_name' => $this->categories()->first()->category_name,
            'rname' => $this->ringtone_name,
            'ringtone' => 'storage/ringtones/'.$this->ringtone_file,
            'download' => $this->ringtone_download_count,
            'status' => 1,
        ];
    }
}
