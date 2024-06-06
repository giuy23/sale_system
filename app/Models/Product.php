<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
  use HasFactory, ImageTrait;

  protected $fillable = [
    'name',
    'description',
    'purchase_price',
    'sale_price',
    'bar_code',
    'quantity',
    'minimum_quantity',
    'state',
    'provider_id',
    'sub_category_id'
  ];

  protected $hidden = ['created_at', 'updated_at'];

  public const path = "images/products";

  public function subCategory(): BelongsTo
  {
    return $this->belongsTo(SubCategory::class);
  }

  public function provider(): BelongsTo
  {
    return $this->belongsTo(Provider::class);
  }

  public function sales(): BelongsToMany
  {
    return $this->belongsToMany(Sale::class);
  }

  public function images()
  {
      return $this->morphMany(Image::class, 'imageable');
  }

  public function image()
  {
      return $this->morphOne(Image::class, 'imageable')->where('is_principal', 1);
  }
}
