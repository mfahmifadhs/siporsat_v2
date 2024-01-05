<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanBbm extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan_bbm";
    protected $primaryKey = "id_bbm";
    public $timestamps = false;

    protected $fillable = [
        'id_bbm',
        'usulan_id',
        'kendaraan_id',
        'bulan_pengadaan',
        'status_pengajuan'
    ];

    public function usulan() {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function aadb() {
        return $this->belongsTo(Aadb::class, 'kendaraan_id');
    }
}
