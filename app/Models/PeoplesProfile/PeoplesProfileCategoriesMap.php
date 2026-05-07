<?php

namespace App\Models\PeoplesProfile;

use Illuminate\Database\Eloquent\Model;

class PeoplesProfileCategoriesMap extends Model
{
    protected $table = 'peoples_profile_category_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function PPcatInfo() {
		return $this->belongsTo('App\Models\PeoplesProfile\PeopleProfileCategories', 'people_profile_category_id', 'id');
	}

	public function PPInfo() {
		return $this->belongsTo('App\Models\PeoplesProfile\PeoplesProfile', 'people_profile_id', 'id');
	}

}
