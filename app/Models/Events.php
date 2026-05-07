<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'events';
    protected $primaryKey = "id";

    public function allImgIds() {
        return $this->hasMany('App\Models\EventImagesMap', 'event_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function categoryIds() {
        return $this->hasMany('App\Models\EventCategoryMap', 'event_id', 'id');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Events','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'EVENT'])->orderBy('display_order', 'asc');
    } 
}
