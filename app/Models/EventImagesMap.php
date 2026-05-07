<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventImagesMap extends Model
{
    protected $table = 'event_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
