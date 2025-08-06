<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hocPhi extends Model
{
    protected $table = 'hoc_phi';
    protected $fillable = ['ho_va_ten', 'so_cccd', 'ngay_sinh', 'dia_chi', 'hang', 'dau_moi', 'le_phi', 'trang_thai','sbd'];
}
