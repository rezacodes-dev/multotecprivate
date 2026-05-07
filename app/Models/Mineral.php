<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mineral extends Model
{
    protected $table = 'mineral';
    protected $primaryKey = "id";

	public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
