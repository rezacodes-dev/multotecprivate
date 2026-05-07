<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class ArticleCategoryImagesMap extends Model
{
    protected $table = 'article_categories_image_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
