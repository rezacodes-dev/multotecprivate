<?php

namespace App\Models\TechResource;

use Illuminate\Database\Eloquent\Model;

class TechResourceIndustriesMap extends Model
{
    protected $table = 'tech_resource_industries_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function industryInfo() {
		return $this->belongsTo('App\Models\Industry\Industries', 'industry_id', 'id');
	}

}
