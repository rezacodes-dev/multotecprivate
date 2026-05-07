<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $table = 'podcast';
    protected $primaryKey = "id";
	public $timestamps = false; 

    public function WebinarCategory() {
		return $this->belongsTo('App\Models\PodcastCategory', 'podcast_category', 'id');
	}
    public function WebinarReferral() {
		return $this->hasMany('App\Models\Referral', 'referral', 'url');
	}
}
