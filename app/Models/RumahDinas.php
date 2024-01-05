<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RumahDinas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_rumah_dinas";
    protected $primaryKey = "id_rumah";
    public $timestamps = false;

    protected $fillable = [
        'id_rumah',
        'golongan',
        'alamat',
        'lokasi_kota',
        'luas_bangunan',
        'luas_tanah',
        'kondisi'
    ];

    public function penghuni() {
        return $this->hasMany(RumahDinasPenghuni::class, 'rumah_id');
    }

    public function fotoRumah() {
        return $this->hasMany(RumahDinasFoto::class, 'rumah_id');
    }
}
