<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanAtk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan_atk";
    protected $primaryKey = "id_permintaan";
    public $timestamps = false;

    protected $fillable = [
        'id_permintaan',
        'usulan_id',
        'atk_id',
        'jumlah_permintaan',
        'jumlah_disetujui',
        'jumlah_penyerahan',
        'harga_permintaan',
        'harga_penyerahan',
        'keterangan_permintaan'
    ];

    public function usulan() {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function atk() {
        return $this->belongsTo(Atk::class, 'atk_id');
    }
}
