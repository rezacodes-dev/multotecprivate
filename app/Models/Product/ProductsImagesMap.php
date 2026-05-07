<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductsImagesMap extends Model
{
    protected $table = 'products_images_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function imageInfo() {
		return $this->belongsTo('App\Models\Media\Images', 'image_id', 'id');
	}
}
