<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_oldat_barang";
    protected $primaryKey = "id_barang";
    public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'unit_kerja_id',
        'kategori_id',
        'nup',
        'merk_tipe',
        'spesifikasi',
        'jumlah',
        'satuan',
        'foto_barang',
        'nilai_perolehan',
        'tahun_perolehan',
        'kondisi_id',
        'status_id'
    ];

    public function unitKerja() {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function kategori() {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id', 'kode_kategori');
    }

    public function pengguna() {
        return $this->hasMany(BarangPengguna::class, 'barang_id');
    }

}
