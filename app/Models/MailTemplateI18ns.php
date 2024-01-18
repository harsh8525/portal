<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplateI18ns extends Model
{
    use HasFactory;
    protected $table = "mail_template_i18ns";
    protected $guarded = [];
}
