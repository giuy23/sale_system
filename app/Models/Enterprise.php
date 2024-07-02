<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Enterprise extends Model
{
  use HasFactory, ImageTrait;

  protected $fillable = [
    'name',
    'name_comercial',
    'description',
    'cell_phone',
    'address',
    'RUC',
  ];

  public const path = 'images/enterprises';

  public function image(): MorphOne
  {
    return $this->morphOne(Image::class, 'imageable');
  }
}
