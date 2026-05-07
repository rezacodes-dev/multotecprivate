<?php

namespace App\Models\TechResource;

use Illuminate\Database\Eloquent\Model;

class TechResourcePersonaMap extends Model
{
    protected $table = 'tech_resource_persona_map';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function personaInfo() {
		return $this->belongsTo('App\Models\TechResource\Personas', 'persona_id', 'id');
	}
}
