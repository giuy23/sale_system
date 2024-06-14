<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
      'sub_total',
      'discount_total',
      'total',
      'igv',
      'state',
      'user_id',
      'client_id',
    ];

    public function client(): BelongsTo
    {
      return $this->belongsTo(Client::class);
    }

    public function creditSale(): HasOne
    {
      return $this->hasOne(CreditSale::class);
    }

    public function products(): BelongsToMany
    {
      return $this->belongsToMany(Product::class);
    }
}
