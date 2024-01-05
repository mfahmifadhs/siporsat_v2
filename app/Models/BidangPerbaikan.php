<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidangPerbaikan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_gdn_bperbaikan";
    protected $primaryKey = "id_bperbaikan";
    public $timestamps = false;

    protected $fillable = [
        'jenis_bperbaikan',
        'bidang_perbaikan'
    ];
}
