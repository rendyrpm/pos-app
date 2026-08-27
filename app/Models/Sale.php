<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_number',
        'user_id',
        'subtotal',
        'discount',
        'total',
        'payment',
        'change_amount',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'payment' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "TRX-{$date}-";

        // Use lockForUpdate to prevent race condition
        $lastSale = self::where('transaction_number', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('transaction_number')
            ->first();

        if ($lastSale) {
            $sequence = (int) substr($lastSale->transaction_number, -5) + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('TRX-%s-%05d', $date, $sequence);
    }
}
