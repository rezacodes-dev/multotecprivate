<?php

namespace App\Models\PageBuilder;

use Illuminate\Database\Eloquent\Model;

class PageBuilder extends Model
{
    protected $table = 'page_builder';
    protected $primaryKey = "id";

    public function images() {
		return $this->hasMany('App\Models\PageBuilder\PageBuilderImages', 'page_builder_id', 'id');
	}

	public function videos() {
		return $this->hasMany('App\Models\PageBuilder\PageBuilderVideos', 'page_builder_id', 'id');
	}

	public function links() {
		return $this->hasMany('App\Models\PageBuilder\PageBuilderLinks', 'page_builder_id', 'id')
		->orderBy('link_order', 'asc');
	}

	public function customlinks() {
		return $this->hasMany('App\Models\PageBuilder\PageBuilderLinks', 'page_builder_id', 'id')
		->where([ 'link_type' => 'CUSTOM_LINKS' ]);
	}

	public function accordion() {
		return $this->hasMany('App\Models\PageBuilder\PageBuilderAccordion', 'page_builder_id', 'id');
	}
}
