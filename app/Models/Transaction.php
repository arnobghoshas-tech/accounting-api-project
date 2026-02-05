<?php

namespace App\Models;

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use App\Models\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_no',
        'company_id',
        'branch_id',
        'from_chart_of_account_id',
        'to_chart_of_account_id',
        'type',
        'transaction_date',
        'amount',
        'reference_no',
        'description',
        'reference_type',
        'reference_id',
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

    public function fromAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'from_chart_of_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'to_chart_of_account_id');
    }

    public function reference()
    {
        return $this->morphTo();
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
