<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyCash extends Model
{
  use HasFactory;

  protected $fillable = ['start_money', 'final_money', 'profit', 'state', 'user_id', 'updated_at'];

  public function expenses(): HasMany
  {
    return $this->hasMany(Expense::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
