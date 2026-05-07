<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $table = 'articles';
    protected $primaryKey = "id";


    public function allImgIds() {
        return $this->hasMany('App\Models\Article\ArticleImagesMap', 'article_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function articleImageId() {
        return $this->hasOne('App\Models\Article\ArticleImagesMap', 'article_id', 'id')
        ->where(['image_type' => 'ARTICLE_IMAGE'])->orderBy('id', 'desc');
    }

    public function ImageIds() {
    	return $this->hasMany('App\Models\Article\ArticleImagesMap', 'article_id', 'id')
    	->where(['image_type' => '']);
    }

    public function FileIds() {
    	return $this->hasMany('App\Models\Article\ArticleFilesMap', 'article_id', 'id')
    	->where(['file_type' => 'OTHER_FILE']);
    }

    public function categoryIds() {
        return $this->hasMany('App\Models\Article\ArticleCategoriesMap', 'article_id', 'id');
    }

    public function categoryOneIds() {
        return $this->hasOne('App\Models\Article\ArticleCategoriesMap', 'article_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Article\Articles','parent_language_id','id') ;
    }

    public function pageBuilderContent() {
        return $this->hasMany('App\Models\PageBuilder\PageBuilder', 'table_id', 'id')
        ->where(['table_type' => 'ARTICLE'])->orderBy('display_order', 'asc');
    } 
}
