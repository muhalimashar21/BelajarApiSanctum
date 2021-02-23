<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Level;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'id_level',
        'nama',
        'alamat',
        'telepon',
        'email',
        'username',
        'password',
        'status_code',
        'status',
    ];

    protected $dates = ['deleted_at'];
    
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function penelitian()
    {
        return $this->hasMany(Penelitian::class);
    }
    public function progres()
    {
        return $this->hasMany(ProgresFisik::class);
    }
    public function level()
    {
        return $this->belongsTo(Level::class,'id_level');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function accessTokens(){
        return $this->hasMany(OauthAccessToken::class);
    }
}
