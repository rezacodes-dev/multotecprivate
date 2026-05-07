<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class DistributorCategories extends Model
{
    protected $table = 'distributor_category';
    protected $primaryKey = "id";


    public function imageInfo() {
        return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
    }

    public function DistributorIds() {
        return $this->hasMany('App\Models\Distributor\DistributorCategoriesMap', 'distributor_category_id', 'id');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Distributor\DistributorCategories','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'DISTRIBUTOR_CATEGORY'])->orderBy('display_order', 'asc');
    }  
}
