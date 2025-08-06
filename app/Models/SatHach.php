<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatHach extends Model
{
    protected $table = 'sat_hach';
    protected $fillable = ['cccd', 'ngay_thi'];
    public function hocVien()
    {
        return $this->belongsTo(hocVien::class, 'cccd', 'cccd');
    }
}
