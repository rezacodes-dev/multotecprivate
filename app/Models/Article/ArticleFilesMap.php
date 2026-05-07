<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class ArticleFilesMap extends Model
{
    protected $table = 'article_files_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function fileInfo() {
		return $this->belongsTo('App\Models\Media\FilesMaster', 'file_id', 'id');
	}
}
