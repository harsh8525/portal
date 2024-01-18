<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class MarkupsAirline extends Model
{
    use HasFactory, Uuids;
    protected $table = 'markups_airline';
    protected $fillable = [
        'markups_id',
        'airline_id',
        'airline_code',
    ];

    public function getMarkupsAirline(){
        return $this->belongsTo('App\Models\Airline', 'airline_id')->with('airlineCodeName');
    }
}
