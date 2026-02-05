<?php

namespace App\Models;

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\ChartOfAccount;
use App\Models\CashBankAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'income_date',
        'reference_no',
        'description',
        'chart_of_account_id',
        'cash_bank_account_id',
        'amount',
        'created_by',
        'updated_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function incomeAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    public function cashBankAccount()
    {
        return $this->belongsTo(CashBankAccount::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'reference');
    }
}
