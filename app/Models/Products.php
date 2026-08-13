<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'description',
        'purchase_price',
        'selling_price',
        'image',
        'minimum_stock',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'minimum_stock' => 'integer',
    ];

    public function getCodeAttribute() { return $this->sku; }
    public function getBuyPriceAttribute() { return $this->purchase_price; }
    public function getSellPriceAttribute() { return $this->selling_price; }
    public function getMinStockAttribute() { return $this->minimum_stock; }
    public function getStockAttribute() { return $this->stockTransactions->where('status', Stock_transaction::STATUS_DITERIMA)->sum(fn ($transaction) => $transaction->type === Stock_transaction::TYPE_MASUK ? $transaction->quantity : -$transaction->quantity); }
    public function getIsLowStockAttribute() { return $this->stock <= $this->minimum_stock; }
    public function getImageUrlAttribute() { 
        if (!$this->image || $this->image === 'noimage.png') {
            return 'https://placehold.co/600x400/374151/9ca3af?text=No+Image';
        }
        return Storage::url($this->image);
    }
    public function getUnitAttribute() { return 'pcs'; }
    public function getIsActiveAttribute() { return true; }
    public function getStockStatusLabelAttribute() { return $this->stock <= 0 ? 'Habis' : ($this->stock <= $this->minimum_stock ? 'Menipis' : 'Aman'); }
    public function getHasPendingTransactionAttribute() { return $this->stockTransactions->contains('status', Stock_transaction::STATUS_PENDING); }

    public function category()
    {
    return $this->belongsTo(Categories::class, 'category_id');
    }

    public function supplier()
    {
    return $this->belongsTo(Suppilers::class, 'supplier_id');
    }

    public function attributes()
    {
    return $this->hasMany(Product_attributes::class, 'product_id');
    }

    public function stockTransactions()
    {
    return $this->hasMany(Stock_transaction::class, 'product_id');
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw(
            "COALESCE((SELECT SUM(CASE WHEN status = 'Diterima' AND type = 'Masuk' THEN quantity WHEN status = 'Diterima' AND type = 'Keluar' THEN -quantity ELSE 0 END) FROM stock_transactions WHERE stock_transactions.product_id = products.id), 0) <= products.minimum_stock"
        );
    }
}
