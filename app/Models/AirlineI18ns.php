<?php

/**
 * @package     Geography
 * @subpackage  AirlineI18ns
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Geography.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlineI18ns extends Model
{
    use HasFactory;
    protected $table = "airline_i18ns";
    protected $guarded = [];
}
