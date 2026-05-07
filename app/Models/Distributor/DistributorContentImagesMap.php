<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class DistributorContentImagesMap extends Model
{
    protected $table = 'distributor_content_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
