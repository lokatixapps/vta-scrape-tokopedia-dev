<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\ProductCategory;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['product_category_id', 'name', 'data'];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
