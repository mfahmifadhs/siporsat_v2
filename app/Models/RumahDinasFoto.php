<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RumahDinasFoto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_rumah_dinas_foto";
    protected $primaryKey = "id_foto";
    public $timestamps = false;

    protected $fillable = [
        'id_foto',
        'rumah_id',
        'nama_file'
    ];

    public function rumahDinas() {
        return $this->belongsTo(RumahDinas::class, 'rumah_id');
    }
}
