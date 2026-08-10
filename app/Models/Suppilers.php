<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppilers extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];
    public function products()
    {
    return $this->hasMany(Products::class, 'supplier_id');
    }
    
    public function stockTransactions()
    {
        return $this->hasMany(Stock_transaction::class, 'supplier_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
