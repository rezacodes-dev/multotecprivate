<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategoryMap extends Model
{
    protected $table = 'event_category_map';
    protected $primaryKey = "id";

    public function catInfo() {
		return $this->belongsTo('App\Models\EventCategories', 'event_category_id', 'id');
	}
}
