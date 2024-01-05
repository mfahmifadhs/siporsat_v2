<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanStnk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan_stnk";
    protected $primaryKey = "id_stnk";
    public $timestamps = false;

    // Usulan Servis dan Perpanjang STNK

    protected $fillable = [
        'id_stnk',
        'usulan_id',
        'kendaraan_id',
        'tanggal_stnk',
        'keterangan',
        'kilometer',
        'tanggal_servis',
        'tanggal_ganti_oli'
    ];

    public function aadb() {
        return $this->belongsTo(Aadb::class, 'kendaraan_id');
    }
}
