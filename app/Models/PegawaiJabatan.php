<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PegawaiJabatan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_pegawai_jabatan";
    protected $primaryKey = "id_jabatan";
    public $timestamps = false;

    protected $fillable = [
        'id_jabatan',
        'nama_jabatan'
    ];
}
