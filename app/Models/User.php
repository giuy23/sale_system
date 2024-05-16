<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

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
    'cellphone',
    'address',
    'name_company',
    'state',
    'role_id',
  ];

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

  public function dailyCash(): HasMany
  {
    return $this->hasMany(DailyCash::class);
  }

  public function role(): BelongsTo
  {
    return $this->belongsTo(Role::class);
  }

  public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
