<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponApplicableCustomer extends Model
{
    use HasFactory;
    protected $table = "coupon_applicable_customer";
    protected $guarded = [];
}
