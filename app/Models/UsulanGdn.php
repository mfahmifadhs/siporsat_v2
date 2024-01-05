<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanGdn extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan_gdn";
    protected $primaryKey = "id_gdn";
    public $timestamps = false;

    protected $fillable = [
        'id_gdn',
        'usulan_id',
        'bperbaikan_id',
        'judul_pekerjaan',
        'deskripsi',
        'keterangan'
    ];

    public function bperbaikan() {
        return $this->belongsTo(BidangPerbaikan::class, 'bperbaikan_id');
    }
}
