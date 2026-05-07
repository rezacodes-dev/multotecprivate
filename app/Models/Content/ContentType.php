<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    protected $table = 'content_type';
    protected $primaryKey = "id";


    public function allImgIds() {
    	return $this->hasMany('App\Models\Content\ContentTypeImagesMap', 'content_type_id', 'id')
    	->orderBy('id', 'desc');
    }


    public function contentIds() {
        return $this->hasMany('App\Models\Content\Contents', 'content_type_id', 'id')
        ->where('status', '=', '1')->where('parent_language_id', '=', '0');
    }


    public function allContents() {
        return $this->hasMany('App\Models\Content\Contents', 'content_type_id', 'id');
    }

}
