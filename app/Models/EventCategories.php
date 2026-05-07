<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategories extends Model
{
    protected $table = 'event_categories';
    protected $primaryKey = "id";

    public function allImgIds() {
        return $this->hasMany('App\Models\EventCategoryImagesMap', 'event_category_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function imageInfo() {
        return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
    }

    public function TotalEvents() {
        return $this->hasMany('App\Models\EventCategoryMap','event_category_id','id') ;
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\EventCategories','parent_language_id','id') ;
    }
}
