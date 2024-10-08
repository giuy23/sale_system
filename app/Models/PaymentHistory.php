<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = ['payment_amount', 'credit_sale_id'];

    public function creditSale(): BelongsTo
    {
      return $this->belongsTo(CreditSale::class);
    }
}
