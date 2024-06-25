<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Provider extends Model
{
  use HasFactory, ImageTrait;

  protected $fillable = ['name', 'document_number', 'name_company', 'cellphone'];

  public const path = 'images/providers';
  protected $hidden = ['created_at', 'updated_at'];
  public function products(): HasMany
  {
    return $this->hasMany(Product::class);
  }

  public function image(): MorphOne
  {
    return $this->morphOne(Image::class, 'imageable');
  }
}
