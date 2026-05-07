<?php

namespace App\Models;

use App\Models\KnowledgeDetails;
use Illuminate\Database\Eloquent\Model;

class KnowledgeHubMaster extends Model
{
    protected $table = 'knowledge_hub';

      public function knowledgeDetails()
    {
        return $this->hasMany(KnowledgeDetails::class, 'kh_id', 'id');
    }
 
}
