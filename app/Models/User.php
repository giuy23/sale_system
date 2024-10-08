<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable, ImageTrait;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'email',
    'password',
    'name',
    'sur_name',
    'document_number',
    'cell_phone',
    'name_company',
    'state',
    'role_id',
    'client_id',
  ];

  public const path = "images/users";

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function products(): HasMany
  {
    return $this->hasMany(Product::class);
  }

  public function sales(): HasMany
  {
    return $this->hasMany(Sale::class);
  }

  public function dailyCashes(): HasMany
  {
    return $this->hasMany(DailyCash::class);
  }

  public function role(): BelongsTo
  {
    return $this->belongsTo(Role::class);
  }
  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class);
  }

  public function image(): MorphOne
  {
    return $this->morphOne(Image::class, 'imageable');
  }
}
