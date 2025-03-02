<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

use function Laravel\Prompts\search;

class User extends Authenticatable implements JWTSubject
{
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'surname',
    'password',
    'avatar',
    'role_id',
    'state', //1 es activo y dos desactivo
    'type_user', // 1 es de tipo cliente y dos es del tipo admin
    'is_instructor',
    'profesion',
    'description'

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
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];
  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }
  public function role()
  {
    return $this->belongsTo(Role::class);
  }
  function scopeFilterAdvance($query,$search,$state)
  {
    if ($search) {
      $query->where("email","like","%".$search."%");
    }
    if ($state) {
      $query->where("state",$state);
    }
    return $query;
  }
}
