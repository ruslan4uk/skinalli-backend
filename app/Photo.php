<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use Sluggable;
    
    protected $table = 'photos';

    protected $fillable = [
        'name', 
        'name_photo', 
        'about', 
        'image_path', 
        'image_preview_path', 
        'active', 
        'keywords',
        'description'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }

    /**
     * Relation table category_photos (ManyToMany)
     */
    public function photoCategory() {
        return $this->belongsToMany('App\Category', 'category_photo', 'photo_id', 'category_id');
    }


    /**
     * Relation table photo_tag (ManyToMany)
     */
    public function photoTag() {
        return $this->belongsToMany('App\Tag', 'photo_tag', 'photo_id', 'tag_id');
    }
}
