<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class NaviMaster extends Model
{
    protected $table = 'navigation_master';
    protected $primaryKey = "id";

    public function childMenu() {
        return $this->hasMany('App\Models\Menu\NaviMaster','parent_page_id','id')->orderBy('oid', 'asc');
    }
}
