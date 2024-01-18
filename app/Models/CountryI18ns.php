<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryI18ns extends Model
{
    use HasFactory;
    protected $table = "country_i18ns";
    protected $guarded = [];
}
