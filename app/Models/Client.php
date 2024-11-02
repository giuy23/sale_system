<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
  use HasFactory;

  protected $fillable = ['id', 'full_name', 'document_number', 'cell_phone', 'state'];

  public function user(): HasOne
  {
    return $this->hasOne(User::class);
  }

  public function sales(): HasMany
  {
    return $this->hasMany(Sale::class);
  }

  public function debts(): HasManyThrough
  {
    return $this->hasManyThrough(CreditSale::class, Sale::class, 'client_id', 'sale_id', 'id', 'id');
  }

}
