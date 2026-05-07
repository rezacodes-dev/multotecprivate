<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class FilesMaster extends Model
{
    protected $table = 'files_master';
    protected $primaryKey = "id";

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }
    
    public function Categories() {
    	return $this->hasMany('App\Models\Media\FileCategoriesMap','file_id','id') ;
    }

    public function getCatSubcat() {
    	return $this->hasOne('App\Models\Media\FileCategoriesMap','file_id','id') ;
    }
}
