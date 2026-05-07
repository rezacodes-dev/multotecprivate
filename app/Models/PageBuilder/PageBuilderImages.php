<?php

namespace App\Models\PageBuilder;

use Illuminate\Database\Eloquent\Model;

class PageBuilderImages extends Model
{
    protected $table = 'page_builder_images';
    protected $primaryKey = "id";

    public function masterImageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'img_id', 'id');
	}
}
