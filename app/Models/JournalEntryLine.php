<?php

namespace App\Models;

use App\Models\User;
use App\Models\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntryLine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'journal_entry_id',
        'chart_of_account_id',
        'debit',
        'credit',
        'created_by',
        'updated_by',
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
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
