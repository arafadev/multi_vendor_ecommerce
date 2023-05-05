<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function scopeFeaturedProducts($query)
    {
        return $query->where([
            ['featured', 1],
            ['status', 1]
        ])
            ->orderBy('id', 'desc')
            ->limit(6);
    }

    public function multiImages()
    {
        return $this->hasMany(MultiImg::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
