<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataAnggaran extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_mta";
    protected $primaryKey = "id_mta";
    public $timestamps = false;

    protected $fillable = [
        'id_mta',
        'unit_kerja_id',
        'kode_mta_4',
        'kode_mta_ctg',
        'kode_mta_5',
        'unit_kerja_id',
        'deskripsi'
    ];

    public function mataAnggaran4() {
        return $this->belongsTo(MataAnggaran4::class, 'kode_mta_4');
    }

    public function mataAnggaranCtg() {
        return $this->belongsTo(MataAnggaranKategori::class, 'kode_mta_ctg');
    }
}
