<?php

namespace App\Models\TechResource;

use Illuminate\Database\Eloquent\Model;

class TechResourceFilesMap extends Model
{
    protected $table = 'tech_resource_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}

}
