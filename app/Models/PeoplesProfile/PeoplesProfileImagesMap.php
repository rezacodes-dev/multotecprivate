<?php

namespace App\Models\PeoplesProfile;

use Illuminate\Database\Eloquent\Model;

class PeoplesProfileImagesMap extends Model
{
    protected $table = 'peoples_profile_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
