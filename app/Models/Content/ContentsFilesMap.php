<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class ContentsFilesMap extends Model
{
    protected $table = 'content_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
