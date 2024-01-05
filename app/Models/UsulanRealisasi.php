<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanRealisasi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan_realisasi";
    protected $primaryKey = "id_realisasi";
    public $timestamps = false;

    protected $fillable = [
        'id_realisasi',
        'usulan_id',
        'mta_id',
        'mta_kode',
        'mta_deskripsi',
        'nilai_realisasi',
        'jenis_realisasi',
        'keterangan'
    ];

    public function usulan() {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function mta() {
        return $this->belongsTo(MataAnggaran::class, 'mta_id');
    }
}
