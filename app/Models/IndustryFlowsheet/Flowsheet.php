<?php

namespace App\Models\IndustryFlowsheet;

use Illuminate\Database\Eloquent\Model;

class Flowsheet extends Model
{
    protected $table = 'flowsheet';
    protected $primaryKey = "id";

    public function flowsheetImageId() {
        return $this->hasOne('App\Models\IndustryFlowsheet\FlowsheetImagesMap', 'flowsheet_id', 'id')
        ->where(['image_type' => 'FLOWSHEET_IMAGE'])->orderBy('id', 'desc');
    }

    public function ImageIds() {
    	return $this->hasMany('App\Models\IndustryFlowsheet\FlowsheetImagesMap', 'flowsheet_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function FileIds() {
    	return $this->hasMany('App\Models\IndustryFlowsheet\FlowsheetFilesMap', 'flowsheet_id', 'id')
    	->where(['file_type' => 'OTHER_FILE']);
    }

    public function categoryIds() {
        return $this->hasMany('App\Models\IndustryFlowsheet\FlowsheetCategoriesMap', 'flowsheet_id', 'id');
    }

    public function categoryOneIds() {
        return $this->hasOne('App\Models\IndustryFlowsheet\FlowsheetCategoriesMap', 'flowsheet_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\IndustryFlowsheet\Flowsheet','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'FLOWSHEET'])->orderBy('display_order', 'asc');
    } 
}
