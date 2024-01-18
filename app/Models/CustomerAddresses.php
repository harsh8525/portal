<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class CustomerAddresses extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getCountry(){
        return $this->belongsTo(Country::class,'country')->with('countryCode');
    }

    public function getState()
    {
        return $this->belongsTo('App\Models\State', 'state', 'id')->withTrashed()->with('stateName');
    }

    public function getCity()
    {
        return $this->belongsTo('App\Models\City', 'city', 'id')->withTrashed()->with('cityCode');
    }
}
