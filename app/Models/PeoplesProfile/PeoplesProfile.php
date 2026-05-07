<?php

namespace App\Models\PeoplesProfile;

use Illuminate\Database\Eloquent\Model;

class PeoplesProfile extends Model
{
    protected $table = 'peoples_profile';
    protected $primaryKey = "id";

    public function ProfileImageId() {
        return $this->hasOne('App\Models\PeoplesProfile\PeoplesProfileImagesMap', 'people_profile_id', 'id')
        ->where(['image_type' => 'MAIN_IMAGE'])->orderBy('id', 'desc');
    }

    public function ImageIds() {
    	return $this->hasMany('App\Models\PeoplesProfile\PeoplesProfileImagesMap', 'people_profile_id', 'id')
    	->where(['image_type' => 'MAIN_IMAGE']);
    }

    public function FileIds() {
    	return $this->hasMany('App\Models\PeoplesProfile\PeoplesProfileFilesMap', 'people_profile_id', 'id')
    	->where(['file_type' => 'OTHER_FILE']);
    }

    public function PP_categoryIds() {
        return $this->hasMany('App\Models\PeoplesProfile\PeoplesProfileCategoriesMap', 'people_profile_id', 'id');
    }

    public function PP_categoryOneIds() {
        return $this->hasOne('App\Models\PeoplesProfile\PeoplesProfileCategoriesMap', 'people_profile_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\PeoplesProfile\PeoplesProfile','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'PEOPLE_PROFILE'])->orderBy('display_order', 'asc');
    } 

    public function imageInfo() {
        return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
    }
}
