<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aadb extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_aadb_kendaraan";
    protected $primaryKey = "id_kendaraan";
    public $timestamps = false;

    protected $fillable = [
        'id_kendaraan',
        'unit_kerja_id',
        'kategori_id',
        'jenis_aadb',
        'kualifikasi',
        'merk_tipe',
        'tahun',
        'no_plat',
        'no_plat_dinas',
        'tanggal_stnk',
        'tanggal_perolehan',
        'nilai_perolehan',
        'keterangan',
        'no_bpkb',
        'no_rangka',
        'no_mesin',
        'foto_kendaraan',
        'kondisi_id',
        'status_id'
    ];

    public function unitKerja() {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function kategori() {
        return $this->belongsTo(KategoriAadb::class, 'kategori_id', 'kode_kategori');
    }

    public function kondisi() {
        return $this->belongsTo(Kondisi::class, 'kondisi_id');
    }

    public function status() {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function pengguna() {
        return $this->hasMany(AadbPengguna::class, 'kendaraan_id');
    }

}
