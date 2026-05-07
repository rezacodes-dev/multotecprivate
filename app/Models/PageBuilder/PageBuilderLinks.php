<?php

namespace App\Models\PageBuilder;

use Illuminate\Database\Eloquent\Model;

class PageBuilderLinks extends Model
{
    protected $table = 'page_builder_links';
    protected $primaryKey = "id";

    public function masterSlugInfo() {
		return $this->belongsTo('App\Models\CmsLinks', 'slug_url', 'slug');
	}
}
