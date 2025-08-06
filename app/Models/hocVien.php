<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hocVien extends Model
{
    use HasFactory;

    protected $table = 'hoc_vien';
    
    protected $fillable = [
        'ho',
        'ten',
        'ngay_sinh',
        'cccd',
        'dia_chi',
        'khoa_hoc',
        'noi_dung_thi',
        'ngay_sat_hach',
        'dau_moi',
        'ly_thuyet',
        'mo_phong',
        'thuc_hanh',
        'duong_truong',
        'hoc_phi',
        'da_thanh_toan'
    ];
    public function dauMoi()
    {
        return $this->belongsTo(DauMoi::class, 'dau_moi', 'ma_dau_moi');
    }
} 