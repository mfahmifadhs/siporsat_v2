<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtkStockopDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_atk_stockop_detail";
    protected $primaryKey = "id_detail";
    public $timestamps = false;

    protected $fillable = [
        'id_detail',
        'stockop_id',
        'atk_id',
        'kuantitas',
        'keterangan'
    ];

    public function stockop() {
        return $this->belongsTo(AtkStockop::class, 'stockop_id');
    }

    public function atk() {
        return $this->belongsTo(Atk::class, 'atk_id');
    }
}
