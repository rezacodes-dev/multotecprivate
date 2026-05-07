<?php

namespace App\Models\IndustryFlowsheet;

use Illuminate\Database\Eloquent\Model;

class FlowsheetImagesMap extends Model
{
    protected $table = 'flowsheet_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
