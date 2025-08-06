<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DauMoi extends Model
{
    protected $table = 'dau_moi';
    protected $fillable = ['ma_dau_moi','ten_dau_moi', 'so_luong_thi_sinh'];
    public function hocVien()
    {
        return $this->hasMany(hocVien::class, 'dau_moi', 'ma_dau_moi');
    }
}
