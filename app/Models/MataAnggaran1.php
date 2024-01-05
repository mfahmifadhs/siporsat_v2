<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataAnggaran1 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_mta_1";
    protected $primaryKey = "id_mta_1";
    public $timestamps = false;

    protected $fillable = [
        'id_mta_1',
        'kode_mta_1',
        'nama_mta_1'
    ];

    public function mataAnggaran2() {
        return $this->hasMany(MataAnggaran2::class, 'kode_mta_1');
    }
}
