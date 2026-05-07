<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Model;

class CareerFilesMap extends Model
{
    protected $table = 'career_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
