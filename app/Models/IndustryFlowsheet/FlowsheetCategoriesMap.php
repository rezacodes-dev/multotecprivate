<?php

namespace App\Models\IndustryFlowsheet;

use Illuminate\Database\Eloquent\Model;

class FlowsheetCategoriesMap extends Model
{
    protected $table = 'flowsheet_category_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function catInfo() {
		return $this->belongsTo('App\Models\IndustryFlowsheet\FlowsheetCategories', 'flowsheet_category_id', 'id');
	}

	public function fsInfo() {
		return $this->belongsTo('App\Models\IndustryFlowsheet\Flowsheet', 'flowsheet_id', 'id');
	}

}
