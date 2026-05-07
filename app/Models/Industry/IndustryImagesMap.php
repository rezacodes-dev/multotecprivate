<?php

namespace App\Models\Industry;

use Illuminate\Database\Eloquent\Model;

class IndustryImagesMap extends Model
{
    protected $table = 'industry_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
