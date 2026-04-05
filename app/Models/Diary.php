<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $fillable = [
        'date',
        'nomenclature_id',
        'debit',
        'credit',
        'invoice_number',
        'nit_cc',
        'business_name',
        'description',
        'parent_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function parent()
    {
        return $this->belongsTo(Diary::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Diary::class, 'parent_id');
    }
}
