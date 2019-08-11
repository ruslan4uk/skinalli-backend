<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Photo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'about' => $this->about,
            'keywords' => $this->keywords,
            'description' => $this->description,
            'active' => $this->active,
            'photo_category' => $this->photoCategory->pluck('id'),
            'photo_tags' => $this->photoTag->pluck('name')
        ];
    }
}
