<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataAnggaranKategori extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_mta_ctg";
    protected $primaryKey = "id_mta_ctg";
    public $timestamps = false;

    protected $fillable = [
        'id_mta_ctg',
        'kode_mta_ctg',
        'nama_mta_ctg'
    ];
}
