<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class DistributorProductCategoriesMap extends Model
{
    protected $table = 'distributor_product_categories_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function catInfo() {
		return $this->belongsTo('App\Models\Product\ProductCategories', 'product_category_id', 'id');
	}
}
