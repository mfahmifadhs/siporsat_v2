<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataAnggaran3 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_mta_3";
    protected $primaryKey = "id_mta_3";
    public $timestamps = false;

    protected $fillable = [
        'id_mta_3',
        'kode_mta_2',
        'kode_mta_3',
        'nama_mta_3'
    ];

    public function mataAnggaran4() {
        return $this->hasMany(MataAnggaran4::class, 'kode_mta_3');
    }
}
