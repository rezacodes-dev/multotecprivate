<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'referral';
    protected $primaryKey = "id";
	public $timestamps = false;

    public function referrals() {
		return $this->belongsToMany('App\Models\Webinar', 'url', 'referral');        
	}
}
