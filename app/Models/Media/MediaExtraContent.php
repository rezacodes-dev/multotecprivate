<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class MediaExtraContent extends Model
{
    protected $table = 'media_extra_content';
    protected $primaryKey = "id";

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}

}
