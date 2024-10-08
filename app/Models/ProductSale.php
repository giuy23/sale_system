<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSale extends Model
{
  use HasFactory;

  protected $fillable = ['sale_id', 'product_id', 'price', 'quantity', 'discount', 'total'];

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }
}
