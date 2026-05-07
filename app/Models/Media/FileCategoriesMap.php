<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class FileCategoriesMap extends Model
{
    protected $table = 'file_categories_map';
    protected $primaryKey = "id";
	public $timestamps = false;

	public function fileInfo() {
        return $this->belongsTo('App\Models\Media\FilesMaster','file_id','id') ;
    }

    public function categoryInfo() {
        return $this->belongsTo('App\Models\Media\FileCategories','file_category_id','id') ;
    }

    public function subcategoryInfo() {
        return $this->belongsTo('App\Models\Media\FileCategories','file_subcategory_id','id') ;
    }

	
}
