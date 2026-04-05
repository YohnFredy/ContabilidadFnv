<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingRuleCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function accountingRules()
    {
        return $this->hasMany(AccountingRule::class);
    }
}
