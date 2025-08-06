<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hocPhan extends Model
{
    protected $table = 'hoc_phan';
    
    protected $fillable = [
        'cccd',
        'ngay_sinh',
        'giao_vien',
        'ngay_hoc',
        'gio_hoc',
        'trang_thai'
    ];
    public function nguoiDung()
    {
        return $this->belongsTo(nguoiDung::class, 'cccd', 'cccd');
    }
} 