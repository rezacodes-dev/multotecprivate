<?php

namespace App\Models\Industry;

use Illuminate\Database\Eloquent\Model;

class Industries extends Model
{
    protected $table = 'industries';
    protected $primaryKey = "id";

	public function industryImageId() {
    	return $this->hasOne('App\Models\Industry\IndustryImagesMap', 'industry_id', 'id')
    	->where(['image_type' => 'INDUSTRY_IMAGE'])->orderBy('id', 'desc');
    }

    public function ImageIds() {
    	return $this->hasMany('App\Models\Industry\IndustryImagesMap', 'industry_id', 'id')
    	->orderBy('id', 'desc');
    }

    public function FileIds() {
    	return $this->hasMany('App\Models\Industry\IndustryFilesMap', 'industry_id', 'id')
    	->where(['file_type' => 'OTHER_FILE']);
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Industry\Industries','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'INDUSTRY'])->orderBy('display_order', 'asc');
    } 
}
