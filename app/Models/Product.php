<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'price',
    'bar_code',
    'quantity',
    'minimum_quantity',
    'state',
    'user_id',
    'sub_category_id'
  ];

  public function subCategory(): BelongsTo
  {
    return $this->belongsTo(SubCategory::class);
  }

  public function provider(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function sales(): BelongsToMany
  {
    return $this->belongsToMany(Sale::class);
  }

  public function images()
  {
      return $this->morphMany(Image::class, 'imageable');
  }

  // public function mainImage()
  // {
  //     return $this->morphOne(Image::class, 'imageable')->where('is_principal', true);
  // }
}
