<?php

namespace App\Models\Distributor;

use Illuminate\Database\Eloquent\Model;

class DistributorContentFilesMap extends Model
{
    protected $table = 'distributor_content_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
