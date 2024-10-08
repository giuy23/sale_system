<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'description', 'type', 'daily_cash_id'];

    public function dailyCash(): BelongsTo
    {
      return $this->belongsTo(DailyCash::class);
    }
}
