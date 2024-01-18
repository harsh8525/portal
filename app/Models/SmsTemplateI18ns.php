<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplateI18ns extends Model
{
    use HasFactory;
    protected $table = "sms_template_i18ns";
    protected $guarded = [];
}
