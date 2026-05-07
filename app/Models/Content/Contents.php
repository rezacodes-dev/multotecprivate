<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Contents extends Model
{
    protected $table = 'contents';
    protected $primaryKey = "id";

    public function bannerImageId() {
        return $this->hasOne('App\Models\Content\ContentsImagesMap', 'content_id', 'id')
        ->where(['image_type' => 'BANNER_IMAGE'])->orderBy('id', 'desc');
    }

    public function ImageIds() {
    	return $this->hasMany('App\Models\Content\ContentsImagesMap', 'content_id', 'id')
    	->where(['image_type' => '']);
    }

    public function FileIds() {
    	return $this->hasMany('App\Models\Content\ContentsFilesMap', 'content_id', 'id')
    	->where(['file_type' => 'OTHER_FILE']);
    }

    public function typeInfo() {
        return $this->belongsTo('App\Models\Content\ContentType', 'content_type_id', 'id');
    }

    public function parentPageInfo() {
        return $this->belongsTo('App\Models\Content\Contents', 'parent_page_id', 'id');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }
    
    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Content\Contents','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'DYNA_CONTENT'])->orderBy('display_order', 'asc');
    }
}
