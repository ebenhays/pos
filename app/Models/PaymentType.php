<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentType extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function dailyTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
