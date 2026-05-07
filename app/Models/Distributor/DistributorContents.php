<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class DistributorContents extends Model
{
    protected $table = 'distributor_contents';
    protected $primaryKey = "id";

    public function imageIds() {
		return $this->hasMany('App\Models\Distributor\DistributorContentImagesMap', 'distributor_content_id', 'id');
	}

	public function fileIds() {
		return $this->hasMany('App\Models\Distributor\DistributorContentFilesMap', 'distributor_content_id', 'id');
	}

	public function distributorInfo() {
		return $this->belongsTo('App\Models\Distributor\Distributor', 'distributor_id', 'id');
	}

	public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Distributor\DistributorContents','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'DISTRIBUTOR_CONTENT'])->orderBy('display_order', 'asc');
    } 
}
