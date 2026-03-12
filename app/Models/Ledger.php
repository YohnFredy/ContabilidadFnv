<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Ledger extends Model
{
    protected $fillable = [
        'nomenclature_id',
        'debit',
        'credit',
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }

    protected function debtorBalance(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->debit > $this->credit ? $this->debit - $this->credit : 0,
        );
    }

    protected function creditorBalance(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->credit > $this->debit ? $this->credit - $this->debit : 0,
        );
    }
}
