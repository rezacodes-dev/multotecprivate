<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class ArticleCategoriesMap extends Model
{
    protected $table = 'article_categories_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function catInfo() {
		return $this->belongsTo('App\Models\Article\ArticleCategories', 'article_category_id', 'id');
	}

}
