<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_atk";
    protected $primaryKey = "id_atk";
    public $timestamps = false;

    protected $fillable = [
        'id_atk',
        'kategori_id',
        'jenis_atk',
        'deskripsi',
        'keterangan_atk',
        'foto_atk',
        'status_id'
    ];

    public function kategori() {
        return $this->belongsTo(KategoriAtk::class, 'kategori_id', 'kode_kategori');
    }

    public function satuan() {
        return $this->hasMany(AtkSatuan::class, 'atk_id')->orderBy('status_id', 'desc');
    }

    public function satuanPick() {
        return $this->hasMany(AtkSatuan::class, 'atk_id')->orderBy('status_id', 'desc')
        ->where('jenis_satuan','distribusi')->where('status_id', 1);
    }
}
