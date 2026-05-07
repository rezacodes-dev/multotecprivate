<?php

namespace App\Models\IndustryFlowsheet;

use Illuminate\Database\Eloquent\Model;

class FlowsheetMarker extends Model
{
    protected $table = 'flowsheet_marker_map';
    protected $primaryKey = "id";
    public $timestamps = false;
}
