<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class nguoiDung extends Model
{
    protected $table = 'nguoi_dung';
    protected $fillable = [
        'ten_nguoi_dung',
        'cccd',
        'ngay_sinh',
        'don_vi',
        'khoa_hoc',
        'trang_thai',
        'ngay_dat',
    ];
}
