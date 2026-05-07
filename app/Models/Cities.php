<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';
    protected $primaryKey = "id";

    public function Province() {
	    return $this->belongsTo('App\Models\Provinces', 'province_id', 'id');
	}
}
