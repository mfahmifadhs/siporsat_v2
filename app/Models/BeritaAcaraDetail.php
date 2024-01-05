<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeritaAcaraDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_bast_detail";
    protected $primaryKey = "id_bast_detail";
    public $timestamps = false;

    protected $fillable = [
        'bast_id',
        'usulan_detail_id',
        'deskripsi',
        'nilai_realisasi'
    ];

    public function bast() {
        return $this->belongsTo(BeritaAcara::class, 'bast_id');
    }

    public function usulanUkt() {
        return $this->belongsTo(UsulanUkt::class, 'usulan_detail_id');
    }

    public function usulanGdn() {
        return $this->belongsTo(UsulanGdn::class, 'usulan_detail_id');
    }

    public function usulanOldat() {
        return $this->belongsTo(UsulanOldat::class, 'usulan_detail_id');
    }

    public function usulanAadb() {
        return $this->belongsTo(UsulanAadb::class, 'usulan_detail_id');
    }

    public function usulanStnk() {
        return $this->belongsTo(UsulanStnk::class, 'usulan_detail_id');
    }

    public function usulanBbm() {
        return $this->belongsTo(UsulanBbm::class, 'usulan_detail_id');
    }

    public function usulanAtk() {
        return $this->belongsTo(UsulanAtk::class, 'usulan_detail_id');
    }
}
