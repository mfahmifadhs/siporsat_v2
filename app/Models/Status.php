<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_status";
    protected $primaryKey = "id_status";
    public $timestamps = false;

    protected $fillable = [
        'id_status',
        'kategori_status',
        'nama_status',
    ];
}
