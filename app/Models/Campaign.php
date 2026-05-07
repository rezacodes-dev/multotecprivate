<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaign';
    protected $primaryKey = "id";
	public $timestamps = false;

    public function SourceType() {
		return $this->belongsTo('App\Models\SourceType', 'source_type', 'id');
	}

	 
}
