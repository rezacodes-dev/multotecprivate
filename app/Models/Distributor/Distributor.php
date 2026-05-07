<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table = 'distributor';
    protected $primaryKey = "id";

    public function imageIds() {
		return $this->hasMany('App\Models\Distributor\DistributorImagesMap', 'distributor_id', 'id');
	}

	public function otherFileIds() {
		return $this->hasMany('App\Models\Distributor\DistributorFilesMap', 'distributor_id', 'id');
	}

	public function distrCategorytIds() {
		return $this->hasMany('App\Models\Distributor\DistributorCategoriesMap', 'distributor_id', 'id');
	}

	public function distrOneCategorytIds() {
		return $this->hasOne('App\Models\Distributor\DistributorCategoriesMap', 'distributor_id', 'id')
		->orderBy('id', 'desc');
	}

	public function distrCategorytOne() {
		return $this->hasOne('App\Models\Distributor\DistributorCategoriesMap', 'distributor_id', 'id')->orderBy('id', 'desc');
	}

	public function distrProductCategoryIds() {
		return $this->hasMany('App\Models\Distributor\DistributorProductCategoriesMap', 'distributor_id', 'id');
	}

	public function distrIndusIds() {
		return $this->hasMany('App\Models\Distributor\DistributorIndustriesMap', 'distributor_id', 'id');
	}

	public function distrContentIds() {
		return $this->hasMany('App\Models\Distributor\DistributorContents', 'distributor_id', 'id');
	}

	public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Distributor\Distributor','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'DISTRIBUTOR'])->orderBy('display_order', 'asc');
    }
}
