<?php

namespace App\Models\PageBuilder;

use Illuminate\Database\Eloquent\Model;

class PageBuilderFiles extends Model
{
    protected $table = 'page_builder_files';
    protected $primaryKey = "id";

    public function masterFileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
