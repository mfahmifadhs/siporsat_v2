<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanAadb extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan_aadb";
    protected $primaryKey = "id_aadb";
    public $timestamps = false;

    protected $fillable = [
        'id_aadb',
        'usulan_id',
        'kategori_id',
        'jenis_aadb',
        'kualifikasi',
        'merk_tipe',
        'tahun',
        'jumlah_pengadaan'
    ];

    public function usulan() {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function aadb() {
        return $this->belongsTo(KategoriAadb::class, 'kategori_id', 'kode_kategori');
    }
}
