<?php

  namespace App\Models;

  use Illuminate\Foundation\Auth\User as Authenticatable;
  use Illuminate\Notifications\Notifiable;
  use Spatie\Permission\Traits\HasRoles;

  class User extends Authenticatable
  {
    use Notifiable, HasRoles;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      "name",
      "alamat",
      "no_telp",
      "email",
      "email_verified_at",
      "password",
      "remember_token",
      "created_at",
      "updated_at",

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'email_verified_at' => 'datetime',
    ];

    public function getDetailUser($id)
    {
      $user = User::with('roles')->find($id);
      return json_decode(json_encode($user), true);
    }
  }
