<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTransaction extends Model
{
    protected $fillable = [
        'batch_no',
        'transaction_date',
        'stock_id',
        'payment_type_id',
        'item_amount',
        'amount_tendered',
        'discount',
        'total_tax',
        'qty_sold',
        'selling_code',
        'user_id'
    ];

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

    public function productSellingType(): BelongsTo
    {
        return $this->belongsTo(DailyTransaction::class, 'selling_code', 'selling_code');
    }
}
