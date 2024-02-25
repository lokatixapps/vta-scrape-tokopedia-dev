<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Product;

class ProductCategory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
