<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table = 'careers';
    protected $primaryKey = "id";

    public function Images() {
		return $this->hasMany('App\Models\Career\CareerImagesMap', 'career_id', 'id');
	}
	public function Files() {
		return $this->hasMany('App\Models\Career\CareerFilesMap', 'career_id', 'id');
	}
	public function cmsLinkInfo() {
        return $this->hasOne('App\Models\CmsLinks', 'slug_url', 'slug');
    }

    public function Language() {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function ChildLanguages() {
        return $this->hasMany('App\Models\Career\Career','parent_language_id','id') ;
    }
}
