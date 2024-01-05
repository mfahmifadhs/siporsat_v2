<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtkStockop extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_atk_stockop";
    protected $primaryKey = "id_stockop";
    public $timestamps = false;

    protected $fillable = [
        'id_stockop',
        'pegawai_id',
        'tanggal_stockop',
        'keterangan'
    ];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function detail() {
        return $this->hasMany(AtkStockopDetail::class, 'stockop_id');
    }
}
