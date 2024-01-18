<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class MarkupsAgent extends Model
{
    use HasFactory, Uuids;
    protected $table = 'markups_agent';
    protected $fillable = [
        'markups_id',
        'agency_id',
        'agent_group_id'
    ];
    
    public function getAgentName(){
        return $this->belongsTo('App\Models\Agency', 'agency_id');
    }
}
