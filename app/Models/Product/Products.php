<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $primaryKey = "id";

	public function ProductCategoryIds() {
		return $this->hasMany('App\Models\Product\ProductCategoriesMap', 'product_id', 'id');
	}

	public function productImages() {
    	return $this->hasMany('App\Models\Product\ProductsImagesMap', 'product_id', 'id')
    	->where(['image_type' => 'MAIN_IMAGE']);
    }

    public function imageIds() {
		return $this->hasMany('App\Models\Product\ProductsImagesMap', 'product_id', 'id')
		->where(['image_type' => '']);
	}

	public function brochureFileIds() {
		return $this->hasMany('App\Models\Product\ProductsFilesMap', 'product_id', 'id')
		->where(['file_type' => 'BROCHURE']);
	}

	public function resourceFileIds() {
		return $this->hasMany('App\Models\Product\ProductsFilesMap', 'product_id', 'id')
		->where(['file_type' => 'TECH_RESOURCE']);
	}

	public function otherFileIds() {
		return $this->hasMany('App\Models\Product\ProductsFilesMap', 'product_id', 'id')
		->where(['file_type' => 'OTHER_FILE']);
	}

	public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Product\Products','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'PRODUCT'])->orderBy('display_order', 'asc');
    }  

    public function allImgIds() {
		return $this->hasMany('App\Models\Product\ProductsImagesMap', 'product_id', 'id')
		->orderBy('id', 'desc');
	}  

}
