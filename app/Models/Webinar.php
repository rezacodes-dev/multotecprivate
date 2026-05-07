<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    protected $table = 'webinar';
    protected $primaryKey = "id";
	public $timestamps = false; 

    public function WebinarCategory() {
		return $this->belongsTo('App\Models\WebinarCategory', 'webinar_category', 'id');
	}
    public function WebinarReferral() {
		return $this->hasMany('App\Models\Referral', 'referral', 'url');
	}
}
