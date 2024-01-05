<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeritaAcara extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_bast";
    protected $primaryKey = "id_bast";
    public $timestamps = false;

    protected $fillable = [
        'id_bast',
        'usulan_id',
        'tanggal_bast',
        'nomor_bast',
        'notes'
    ];

    public function usulan() {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function detail() {
        return $this->hasMany(BeritaAcaraDetail::class, 'bast_id');
    }
}
