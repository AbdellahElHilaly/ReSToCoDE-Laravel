<?php

namespace App\Models;

use App\Models\Ruletype;
use App\Models\Permission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable Implements JWTSubject
{
    use HasFactory, Notifiable;


    protected $guarded = [];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }



    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }

    public function token()
    {
        return $this->belongsTo(Token::class);
    }	


}
