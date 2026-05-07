<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = 'countries';
    protected $primaryKey = "id";

    public function Region() {
	    return $this->belongsTo('App\Models\Regions', 'region_id', 'id');
	}
}
