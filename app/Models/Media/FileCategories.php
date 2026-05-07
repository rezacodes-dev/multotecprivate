<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class FileCategories extends Model
{
    protected $table = 'file_categories';
    protected $primaryKey = "id";

    public function fileIds() {
        return $this->hasMany('App\Models\Media\FileCategoriesMap','file_category_id','id') ;
    }

    public function parent() {
        return $this->belongsTo('App\Models\Media\FileCategories','parent_category_id','id') ;
    }
}
