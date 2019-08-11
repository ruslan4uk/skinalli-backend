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
            'name_photo' => $this->name_photo,
            'about' => $this->about,
            'keywords' => $this->keywords,
            'description' => $this->description,
            'active' => $this->active,
            'photo_category' => $this->photoCategory->pluck('id'),
            'photo_tags' => $this->photoTag->pluck('name'),
            'image_path' => $this->image_path,
            'image_preview_path' => $this->image_preview_path,
            'image_lazy' => $this->image_lazy,

            'image_path_webp' => $this->image_path_webp,
            'image_preview_path_webp' => $this->image_preview_path_webp,
            'image_lazy_webp' => $this->image_lazy_webp,
        ];
    }
}
