<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAtk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_atk_kategori";
    protected $primaryKey = "id_kategori";
    public $timestamps = false;

    protected $fillable = [
        'id_kategori',
        'kode_kategori',
        'kategori_atk'
    ];
}
