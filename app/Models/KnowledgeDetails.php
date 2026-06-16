<?php

namespace App\Models;

use App\Models\KnowledgeHubMaster;
use Illuminate\Database\Eloquent\Model;

class KnowledgeDetails extends Model
{
    protected $table = 'knowledge_hub_details';

    protected $guarded = [];

  
    public function brochureMaster()
    {
        return $this->belongsTo(KnowledgeHubMaster::class, 'kh_id', 'id');
    }

  
    
}
