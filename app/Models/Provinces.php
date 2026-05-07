<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $table = 'provinces';
    protected $primaryKey = "id";

    public function Country() {
	    return $this->belongsTo('App\Models\Countries', 'country_id', 'id');
	}
}
