<?php

namespace App\Models\IndustryFlowsheet;

use Illuminate\Database\Eloquent\Model;

class FlowsheetCategories extends Model
{
    protected $table = 'flowsheet_category';
    protected $primaryKey = "id";


    public function imageInfo() {
        return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
    }

    
    public function flowsheetIds() {
        return $this->hasMany('App\Models\IndustryFlowsheet\FlowsheetCategoriesMap', 'flowsheet_category_id', 'id');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\IndustryFlowsheet\FlowsheetCategories','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'FLOWSHEET_CATEGORY'])->orderBy('display_order', 'asc');
    } 

}
