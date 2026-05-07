<?php

namespace App\Models\TechResource;

use Illuminate\Database\Eloquent\Model;

class TechResourceImagesMap extends Model
{
    protected $table = 'tech_resource_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}

}
