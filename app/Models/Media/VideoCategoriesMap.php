<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class VideoCategoriesMap extends Model
{
    protected $table = 'video_categories_map';
    protected $primaryKey = "id";
	public $timestamps = false;

	public function VideoInfo() {
        return $this->belongsTo('App\Models\Media\Videos','video_id','id') ;
    }

    public function categoryInfo() {
        return $this->belongsTo('App\Models\Media\VideoCategories','video_category_id','id') ;
    }

    public function subcategoryInfo() {
        return $this->belongsTo('App\Models\Media\VideoCategories','video_subcategory_id','id') ;
    }

	
}
