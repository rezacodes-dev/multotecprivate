<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class ArticleImagesMap extends Model
{
    protected $table = 'article_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
