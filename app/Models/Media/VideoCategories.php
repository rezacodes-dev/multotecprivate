<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class VideoCategories extends Model
{
    protected $table = 'video_categories';
    protected $primaryKey = "id";

    public function AllVideos() {
        return $this->hasMany('App\Models\Media\VideoCategoriesMap','video_category_id','id') ;
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function parent() {
        return $this->belongsTo('App\Models\Media\VideoCategories','parent_category_id','id') ;
    }
}
