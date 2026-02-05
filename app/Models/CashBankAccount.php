<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashBankAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'chart_of_account_id',
        'name',
        'account_number',
        'bank_name',
        'branch',
        'contact',
        'email',
        'opening_balance',
        'opening_balance_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
