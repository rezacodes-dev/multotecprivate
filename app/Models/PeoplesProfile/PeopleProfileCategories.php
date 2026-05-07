<?php

namespace App\Models\PeoplesProfile;

use Illuminate\Database\Eloquent\Model;

class PeopleProfileCategories extends Model
{
    protected $table = 'people_profile_categories';
    protected $primaryKey = "id";

    public function profileIds() {
        return $this->hasMany('App\Models\PeoplesProfile\PeoplesProfileCategoriesMap', 'people_profile_category_id', 'id');
    }

    public function orderByDisplay() {
        return $this->belongsToMany('App\Models\PeoplesProfile\PeoplesProfile', 'peoples_profile_category_map', 'people_profile_category_id', 'people_profile_id')->orderBy('peoples_profile.display_order', 'asc');
    }

    public function imageInfo() {
        return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
    }

    
    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\PeoplesProfile\PeopleProfileCategories','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'PEOPLE_PROFILE_CATEGORY'])->orderBy('display_order', 'asc');
    } 

}
