<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class ContentsImagesMap extends Model
{
    protected $table = 'content_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
