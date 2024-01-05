<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormUsulan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_jenis_form";
    protected $primaryKey = "id_jenis_form";
    public $timestamps = false;

    protected $fillable = [
        'id_jenis_form',
        'kategori',
        'klasifikasi',
        'nama_form'
    ];
}
