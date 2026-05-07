<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class DistributorCategoriesMap extends Model
{
    protected $table = 'distributor_categories_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function catInfo() {
		return $this->belongsTo('App\Models\Distributor\DistributorCategories', 'distributor_category_id', 'id');
	}

	public function distrbInfo() {
		return $this->belongsTo('App\Models\Distributor\Distributor', 'distributor_id', 'id');
	}
}
