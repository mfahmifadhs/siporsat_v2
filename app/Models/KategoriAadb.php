<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAadb extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_aadb_kategori";
    protected $primaryKey = "id_kategori";
    public $timestamps = false;

    protected $fillable = [
        'id_kategori',
        'kode_kategori',
        'kategori_aadb'
    ];

    public function aadb() {
        return $this->hasMany(Aadb::class, 'kategori_id');
    }
}
