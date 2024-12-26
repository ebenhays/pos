<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTransaction extends Model
{
    protected $guarded = ['id'];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
