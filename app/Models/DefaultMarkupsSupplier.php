<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\Uuids;

class DefaultMarkupsSupplier extends Model
{
    use HasFactory, Uuids;

    protected $table = 'default_markups_suppliers';
    protected $guarded = [];
    protected $fillable = [
        'default_markups_id',
        'supplier_id',
    ];

    public function geDefaultMarkupsSupplier(){
        return $this->belongsTo('App\Models\Suppliers', 'supplier_id');
    }
}
