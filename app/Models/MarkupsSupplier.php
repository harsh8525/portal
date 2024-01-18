<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class MarkupsSupplier extends Model
{
    use HasFactory, Uuids;
    protected $table = 'markups_supplier';
    protected $fillable = [
        'markups_id',
        'supplier_id',
    ];

    public function getMarkupsSupplier(){
        return $this->belongsTo('App\Models\Suppliers', 'supplier_id');
    }
}
