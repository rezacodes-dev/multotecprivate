<?php

namespace App\Models\Industry;

use Illuminate\Database\Eloquent\Model;

class IndustryFilesMap extends Model
{
    protected $table = 'industry_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
