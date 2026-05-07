<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductCategoriesFilesMap extends Model
{
    protected $table = 'product_categories_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
