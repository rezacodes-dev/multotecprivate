<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class ArticleCategories extends Model
{
    protected $table = 'article_categories';
    protected $primaryKey = "id";


    public function allImgIds() {
        return $this->hasMany('App\Models\Article\ArticleCategoryImagesMap', 'article_category_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function imageInfo() {
        return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
    }

    public function articleIds() {
        return $this->hasMany('App\Models\Article\ArticleCategoriesMap', 'article_category_id', 'id');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Article\ArticleCategories','parent_language_id','id') ;
    }
}
