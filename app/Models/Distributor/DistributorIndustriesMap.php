<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class DistributorIndustriesMap extends Model
{
    protected $table = 'distributor_industries_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function industryInfo() {
		return $this->belongsTo('App\Models\Industry\Industries', 'industry_id', 'id');
	}
}
