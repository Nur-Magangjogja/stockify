<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock_transaction extends Model
{
    use HasFactory;

    protected $table = 'stock_transactions';

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'date',
        'status',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'date' => 'date',
    ];

    public const TYPE_IN = 'Masuk';
    public const TYPE_OUT = 'Keluar';
    public const STATUS_PENDING = 'Pending';
    public const STATUS_CONFIRMED = 'Diterima';
    public const STATUS_CANCELLED = 'Ditolak';
    public const STATUS_DIKELUARKAN = 'Dikeluarkan';

    public const TYPE_MASUK = self::TYPE_IN;
    public const TYPE_KELUAR = self::TYPE_OUT;
    public const STATUS_DITERIMA = self::STATUS_CONFIRMED;
    public const STATUS_DITOLAK = self::STATUS_CANCELLED;

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Suppilers::class);
    }

    public function getTransactionDateAttribute()
    {
        return $this->date;
    }

    public function getTotalQuantityAttribute(): int
    {
        return $this->quantity;
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status;
    }

    public function getStockBeforeAttribute(): int
    {
        $transactions = $this->product?->stockTransactions ?? collect();

        return $transactions
            ->filter(fn (self $transaction) => $this->isBefore($transaction))
            ->sum(fn (self $transaction) => $transaction->signedQuantity());
    }

    public function getStockAfterAttribute(): int
    {
        $stockBefore = $this->stock_before;

        if ($this->status !== self::STATUS_CONFIRMED) {
            return $stockBefore;
        }

        return $stockBefore + $this->signedQuantity();
    }

    private function isBefore(self $transaction): bool
    {
        if ($transaction->id === $this->id || $transaction->status !== self::STATUS_CONFIRMED) {
            return false;
        }

        $transactionDate = $transaction->date?->toDateString();
        $thisDate = $this->date?->toDateString();

        return $transactionDate < $thisDate
            || ($transactionDate === $thisDate && $transaction->id < $this->id);
    }

    private function signedQuantity(): int
    {
        return $this->type === self::TYPE_IN ? (int) $this->quantity : -(int) $this->quantity;
    }

    public function scopeIn($query)
    {
        return $query->where('type', self::TYPE_IN);
    }

    public function scopeOut($query)
    {
        return $query->where('type', self::TYPE_OUT);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }
}