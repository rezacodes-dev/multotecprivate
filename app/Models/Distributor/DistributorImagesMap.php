<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class DistributorImagesMap extends Model
{
    protected $table = 'distributor_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
