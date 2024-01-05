<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kondisi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_kondisi";
    protected $primaryKey = "id_kondisi";
    public $timestamps = false;

    protected $fillable = [
        'id_kondisi',
        'nama_kondisi'
    ];
}
