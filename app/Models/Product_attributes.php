<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_attributes extends Model
{
    use HasFactory;
    protected $table = 'product_attributes';
    protected $fillable = [
        'product_id',
        'name',
        'value',
    ];
    public function product()
{
    return $this->belongsTo(Products::class, 'product_id');
}
}
