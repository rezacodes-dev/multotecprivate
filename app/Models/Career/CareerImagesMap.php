<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Model;

class CareerImagesMap extends Model
{
    protected $table = 'career_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
