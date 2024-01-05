<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usulan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan";
    protected $primaryKey = "id_usulan";
    public $timestamps = false;

    protected $fillable = [
        'id_usulan',
        'user_id',
        'pegawai_id',
        'form_id',
        'tanggal_usulan',
        'nomor_usulan',
        'keterangan',
        'keterangan_tolak',
        'status_pengajuan_id',
        'status_proses_id',
        'otp_1',
        'otp_2',
        'otp_3',
        'otp_4',
        'otp_5'
    ];

    public function form() {
        return $this->belongsTo(FormUsulan::class, 'form_id');
    }

    public function statusPengajuan() {
        return $this->belongsTo(Status::class, 'status_pengajuan_id');
    }

    public function statusProses() {
        return $this->belongsTo(Status::class, 'status_proses_id');
    }

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function usulanOldat() {
        return $this->hasMany(UsulanOldat::class, 'usulan_id');
    }

    public function usulanUkt() {
        return $this->hasMany(UsulanUkt::class, 'usulan_id');
    }

    public function usulanGdn() {
        return $this->hasMany(UsulanGdn::class, 'usulan_id');
    }

    public function usulanStnk() {
        return $this->hasMany(UsulanStnk::class, 'usulan_id')->where('keterangan','!=','false');
    }

    public function usulanAadb() {
        return $this->hasMany(UsulanAadb::class, 'usulan_id');
    }

    public function usulanAtk() {
        return $this->hasMany(UsulanAtk::class, 'usulan_id');
    }

    public function usulanBbm() {
        return $this->hasMany(UsulanBbm::class, 'usulan_id');
    }

    public function realisasi() {
        return $this->hasMany(UsulanRealisasi::class, 'usulan_id');
    }

    public function bast() {
        return $this->hasMany(BeritaAcara::class, 'usulan_id');
    }
}
