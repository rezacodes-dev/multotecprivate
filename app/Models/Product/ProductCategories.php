<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    protected $table = 'product_categories';
    protected $primaryKey = "id";

    public function ProductIds() {
		return $this->hasMany('App\Models\Product\ProductsCategoriesMap', 'product_category_id', 'id');
	}

	public function myParent() {
		return $this->belongsTo('App\Models\Product\ProductCategories', 'parent_id', 'id');
	}

    public function child() {
		return $this->hasMany('App\Models\Product\ProductCategories', 'parent_id', 'id');
	}

	public function catInfo() {
		return $this->belongsTo('App\Models\Product\ProductCategories', 'parent_id', 'id');
	}

	public function allImgIds() {
		return $this->hasMany('App\Models\Product\ProductCategoriesImagesMap', 'product_category_id', 'id')
		->orderBy('id', 'desc');
	}

	public function bannerId() {
		return $this->hasOne('App\Models\Product\ProductCategoriesImagesMap', 'product_category_id', 'id')
		->where(['image_type' => 'PAGE_BANNER']);
	}

	public function imageIds() {
		return $this->hasMany('App\Models\Product\ProductCategoriesImagesMap', 'product_category_id', 'id')
		->where(['image_type' => 'PRO_CAT_IMG']);
	}

	public function otherFileIds() {
		return $this->hasMany('App\Models\Product\ProductCategoriesFilesMap', 'product_category_id', 'id')
		->where(['file_type' => 'OTHER_FILE']);
	}

	public function resourceFileIds() {
		return $this->hasMany('App\Models\Product\ProductCategoriesFilesMap', 'product_category_id', 'id')
		->where(['file_type' => 'TECH_RESOURCE']);
	}

	public function brochureFileIds() {
		return $this->hasMany('App\Models\Product\ProductCategoriesFilesMap', 'product_category_id', 'id')
		->where(['file_type' => 'BROCHURE']);
	}

	public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Product\ProductCategories','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'PRODUCT_CATEGORY'])->orderBy('display_order', 'asc');
    } 

}
