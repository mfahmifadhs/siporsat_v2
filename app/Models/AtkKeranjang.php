<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtkKeranjang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_atk_keranjang";
    protected $primaryKey = "id_keranjang";
    public $timestamps = false;

    protected $fillable = [
        'id_keranjang',
        'user_id',
        'atk_id',
        'kuantitas',
        'status_id'
    ];

    public function atk() {
        return $this->belongsTo(Atk::class, 'atk_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
