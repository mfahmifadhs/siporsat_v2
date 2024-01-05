<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataAnggaran2 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_mta_2";
    protected $primaryKey = "id_mta_2";
    public $timestamps = false;

    protected $fillable = [
        'id_mta_2',
        'kode_mta_1',
        'kode_mta_2',
        'nama_mta_2'
    ];

    public function mataAnggaran3() {
        return $this->hasMany(MataAnggaran3::class, 'kode_mta_2');
    }
}
