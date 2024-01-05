<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AadbPengguna extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_aadb_pengguna";
    protected $primaryKey = "id_pengguna";
    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'tanggal_pengguna',
        'kendaraan_id',
        'pegawai_id',
        'keterangan',
        'status_id'
    ];

    public function aadb() {
        return $this->belongsTo(Aadb::class, 'kendaraan_id');
    }

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function status() {
        return $this->belongsTo(Status::class, 'status_id');
    }

}
