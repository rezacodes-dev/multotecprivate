<?php

namespace App\Models\FrmBuilder;

use Illuminate\Database\Eloquent\Model;

class FrmCategories extends Model
{
    protected $table = 'frm_category';
    protected $primaryKey = "id";

    public function TotalForms() {
        return $this->hasMany('App\Models\FrmBuilder\FrmMaster','category_id','id') ;
    }
}
