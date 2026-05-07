<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrochureMaster extends Model
{
    protected $table = 'brochure_master';

      public function brochureDetails()
    {
        return $this->hasMany(BrochureDetails::class, 'brochure_id', 'id');
    }
 
}
