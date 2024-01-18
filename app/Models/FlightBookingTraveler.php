<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Traits\Uuids;

class FlightBookingTraveler extends Model
{
    use HasFactory, Uuids;
    protected $table = 'flight_booking_travelers';
    protected $guarded = [];
}
