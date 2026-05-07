<?php

namespace App\Models\FrmBuilder;

use Illuminate\Database\Eloquent\Model;

class FrmMaster extends Model
{
    protected $table = 'frm_master';
    protected $primaryKey = "id";

    public function Category() {
	    return $this->hasOne('App\Models\FrmBuilder\FrmCategories', 'id', 'category_id');
	}
}
