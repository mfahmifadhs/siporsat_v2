<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitKerja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_unit_kerja";
    protected $primaryKey = "id_unit_kerja";
    public $timestamps = false;

    protected $fillable = [
        'id_unit_kerja',
        'unit_utama_id',
        'pegawai_id',
        'nama_unit_kerja',
        'alokasi_anggaran'
    ];

    public function unitUtama() {
        return $this->belongsTo(UnitUtama::class, 'unit_utama_id');
    }

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function aadb() {
        return $this->hasMany(Aadb::class, 'unit_kerja_id')
            ->orderBy('kualifikasi','ASC')->orderBy('jenis_aadb','DESC')->orderBy('no_plat', 'DESC')
            ->where('status_id', 991)->where('no_plat', '!=', '');
    }
}
