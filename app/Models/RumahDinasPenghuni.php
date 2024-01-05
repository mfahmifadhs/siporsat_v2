<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RumahDinasPenghuni extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_rumah_dinas_penghuni";
    protected $primaryKey = "id_penghuni";
    public $timestamps = false;

    protected $fillable = [
        'id_penghuni',
        'rumah_id',
        'pegawai_id',
        'nomor_sip',
        'sertifikat',
        'pbb',
        'imb',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_penghuni'
    ];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function rumahDinas() {
        return $this->belongsTo(RumahDinas::class, 'rumah_id');
    }
}
