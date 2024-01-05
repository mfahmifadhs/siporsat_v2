<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'pegawai_id',
        'username',
        'password',
        'password_teks',
        'google2fa_secret',
        'sess_modul',
        'sess_form_id',
        'sess_bast_id',
        'status_google2fa',
        'status_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function status() {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function keranjang() {
        return $this->hasMany(AtkKeranjang::class, 'user_id');
    }
}
