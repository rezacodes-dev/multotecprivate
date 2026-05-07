<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductCategoriesMap extends Model
{
    protected $table = 'product_categories_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function ProductInfo() {
		return $this->belongsTo('App\Models\Product\Products', 'product_id', 'id');
	}

	public function ProductCategoryInfo() {
		return $this->belongsTo('App\Models\Product\ProductCategories', 'product_category_id', 'id');
	}
}
