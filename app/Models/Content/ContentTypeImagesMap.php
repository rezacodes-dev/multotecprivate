<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class ContentTypeImagesMap extends Model
{
    protected $table = 'content_type_images_map';
    protected $primaryKey = "id";

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}

}
