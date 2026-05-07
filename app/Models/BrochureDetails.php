<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrochureDetails extends Model
{
    protected $table = 'brochure_details';

    protected $guarded = [];

  
    public function brochureMaster()
    {
        return $this->belongsTo(BrochureMaster::class, 'brochure_id', 'id');
    }

    public function brochureProducts()
    {
        return $this->hasMany(BrochureProductDetails::class, 'brochure_details_id', 'id');
    }
    
}
