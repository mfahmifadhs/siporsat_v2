<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanOldat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan_oldat";
    protected $primaryKey = "id_oldat";
    public $timestamps = false;

    protected $fillable = [
        'id_oldat',
        'usulan_id',
        'kategori_barang_id',
        'barang_id',
        'merk_tipe',
        'spesifikasi',
        'jumlah_pengadaan',
        'estimasi_harga',
        'keterangan_kerusakan'
    ];

    public function usulan() {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function kategori() {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id', 'kode_kategori');
    }

    public function barang() {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
