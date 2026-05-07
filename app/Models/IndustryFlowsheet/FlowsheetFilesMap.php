<?php

namespace App\Models\IndustryFlowsheet;

use Illuminate\Database\Eloquent\Model;

class FlowsheetFilesMap extends Model
{
    protected $table = 'flowsheet_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
