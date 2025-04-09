<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentType extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function dailyTransaction(): BelongsTo
    {
        return $this->belongsTo(DailyTransaction::class);
    }
}
