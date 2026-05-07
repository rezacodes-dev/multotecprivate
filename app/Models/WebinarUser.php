<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebinarUser extends Model
{
    protected $table = 'webinar_users';
    protected $primaryKey = "id";
	public $timestamps = false; 

    public function WebinarCategory() {
		return $this->belongsTo('App\Models\WebinarCategory', 'webinar_category', 'id');
	}
    public function webinar() {
		return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
	}
}
