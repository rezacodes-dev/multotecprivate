<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastTopic extends Model
{
    protected $table = 'podcast_topic';
    protected $primaryKey = "id";
	public $timestamps = false; 
}
