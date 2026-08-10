<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categories extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'description',
    ];

    public function products()
    {
    return $this->hasMany(Products::class, 'category_id');
    }

    //Auto-generate
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->description)) {
                $category->description = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            $category->description = Str::slug($category->name);
        });
    }
}

