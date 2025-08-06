<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauHinh extends Model
{
    protected $table = 'cau_hinh';
    protected $fillable = ['id', 'so_luong_thi_sinh', 'ngay_thi', 'ghi_chu', 'ngan_hang', 'so_tai_khoan','chu_tai_khoan'];
    
    protected $casts = [
        'ngay_thi' => 'string',
    ];
}
