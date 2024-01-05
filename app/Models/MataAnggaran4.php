<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataAnggaran4 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_mta_4";
    protected $primaryKey = "id_mta_4";
    public $timestamps = false;

    protected $fillable = [
        'id_mta_4',
        'kode_mta_3',
        'kode_mta_4',
        'nama_mta_4'
    ];

    public function mataAnggaran() {
        return $this->hasMany(MataAnggaran::class, 'kode_mta_4');
    }
}
