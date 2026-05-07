<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MineralProcess extends Model
{
    protected $table = 'mineral_processing';
    protected $primaryKey = "id";

	public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
