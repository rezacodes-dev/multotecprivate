<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastUser extends Model
{
    protected $table = 'podcast_users';
    protected $primaryKey = "id";
	public $timestamps = false; 

    public function WebinarCategory() {
		return $this->belongsTo('App\Models\PodcastCategory', 'podcast_category', 'id');
	}
    public function WebinarReferral() {
		return $this->hasMany('App\Models\Referral', 'referral', 'url');
	}
}
