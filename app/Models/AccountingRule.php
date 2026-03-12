<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingRule extends Model
{
    protected $fillable = [
        'name',
        'nomenclature_id_1',
        'nature_1',
        'nomenclature_id_2',
        'nature_2',
        'nomenclature_id_3',
        'nature_3',
        'nomenclature_id_4',
        'nature_4',
        'nomenclature_id_5',
        'nature_5',
        'nomenclature_id_6',
        'nature_6',
    ];

    public const NATURES = ['Débito', 'Crédito'];

    public function nomenclature1(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id_1');
    }

    public function nomenclature2(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id_2');
    }

    public function nomenclature3(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id_3');
    }

    public function nomenclature4(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id_4');
    }

    public function nomenclature5(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id_5');
    }

    public function nomenclature6(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id_6');
    }
}
