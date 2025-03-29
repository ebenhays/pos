<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOnCreditTransactions extends Model
{
    protected $guarded = ['id'];

    protected $table = "customer_credit_transactions";

    public function dailyTransaction()
    {
        return $this->belongsTo(DailyTransaction::class, 'batch_no', 'batch_no');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
