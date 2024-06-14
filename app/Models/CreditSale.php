<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditSale extends Model
{
    use HasFactory;

    protected $fillable = [
      'amount_payable',
      'remaining_amount',
      'description',
      'is_paid',
      'sale_id',
    ];

    public function paymentHistories(): HasMany
    {
      return $this->hasMany(PaymentHistory::class);
    }

    public function sale(): BelongsTo
    {
      return $this->belongsTo(Sale::class);
    }
}
