<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanUkt extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan_ukt";
    protected $primaryKey = "id_ukt";
    public $timestamps = false;

    protected $fillable = [
        'id_ukt',
        'usulan_id',
        'judul_pekerjaan',
        'deskripsi',
        'keterangan'
    ];
}
