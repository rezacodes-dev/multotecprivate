<?php

namespace App\Models\TechResource;

use Illuminate\Database\Eloquent\Model;

class TechResourceProcatMap extends Model
{
    protected $table = 'tech_resource_procat_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function procatInfo() {
		return $this->belongsTo('App\Models\Product\ProductCategories', 'product_category_id', 'id');
	}

}
