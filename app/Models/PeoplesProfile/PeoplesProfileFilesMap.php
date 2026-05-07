<?php

namespace App\Models\PeoplesProfile;

use Illuminate\Database\Eloquent\Model;

class PeoplesProfileFilesMap extends Model
{
    protected $table = 'peoples_profile_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
