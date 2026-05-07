<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    protected $table = 'videos';
    protected $primaryKey = "id";

    public function CategoriesInfo() {
        return $this->hasMany('App\Models\Media\VideoCategoriesMap','video_id','id') ;
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function getCatSubcat() {
    	return $this->hasOne('App\Models\Media\VideoCategoriesMap','video_id','id') ;
    }
}
