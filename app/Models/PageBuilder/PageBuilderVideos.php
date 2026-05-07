<?php

namespace App\Models\PageBuilder;

use Illuminate\Database\Eloquent\Model;

class PageBuilderVideos extends Model
{
    protected $table = 'page_builder_videos';
    protected $primaryKey = "id";

    public function masterVideoInfo() {
		return $this->belongsTo('App\Models\Media\Videos', 'video_id', 'id');
	}
}
