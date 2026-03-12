<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'category'];

    public const CATEGORIES = [
        'Activos Corrientes',
        'Pasivos Corrientes',
        'Patrimonio',
        'Gastos',
        'Activos No Corrientes',
        'Ingresos',
        'Costos',
    ];
}
