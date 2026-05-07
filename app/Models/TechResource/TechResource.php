<?php

namespace App\Models\TechResource;

use Illuminate\Database\Eloquent\Model;

class TechResource extends Model
{
    protected $table = 'tech_resource';
    protected $primaryKey = "id";

    public function personaIds() {
    	return $this->hasMany('App\Models\TechResource\TechResourcePersonaMap', 'tech_resource_id', 'id');
    }

    public function industryIds() {
    	return $this->hasMany('App\Models\TechResource\TechResourceIndustriesMap', 'tech_resource_id', 'id');
    }

    public function procatIds() {
    	return $this->hasMany('App\Models\TechResource\TechResourceProcatMap', 'tech_resource_id', 'id');
    }

    public function ImageIds() {
    	return $this->hasMany('App\Models\TechResource\TechResourceImagesMap', 'tech_resource_id', 'id')
    	->orderBy('id', 'desc');
    }

    public function FileIds() {
    	return $this->hasMany('App\Models\TechResource\TechResourceFilesMap', 'tech_resource_id', 'id')
    	->orderBy('id', 'desc');
    }

    public function documentId() {
    	return $this->hasOne('App\Models\TechResource\TechResourceFilesMap', 'tech_resource_id', 'id')
    	->where(['file_type' => 'DOCUMENT'])->orderBy('id', 'desc');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\TechResource\TechResource','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'TECH_RESOURCE'])->orderBy('display_order', 'asc');
    }

}
