<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrochureProductDetails extends Model
{
    protected $table = 'brochure_product_details';

    protected $guarded = [];


      public function brochureDetails()
    {
        return $this->belongsTo(BrochureDetails::class, 'brochure_details_id', 'id');
    }
    
}
