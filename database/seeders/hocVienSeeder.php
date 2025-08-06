<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\hocVien;

class hocVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hocViens = [
            [
                'ho' => 'Nguyễn',
                'ten' => 'Văn An',
                'ngay_sinh' => '1995-03-15',
                'cccd' => '123456789012',
                'dia_chi' => '123 Đường ABC, Quận 1, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B2',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-06-15',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 5000000
            ],
            [
                'ho' => 'Trần',
                'ten' => 'Thị Bình',
                'ngay_sinh' => '1990-07-22',
                'cccd' => '987654321098',
                'dia_chi' => '456 Đường XYZ, Quận 3, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B1',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-06-20',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 4500000
            ],
            [
                'ho' => 'Lê',
                'ten' => 'Văn Cường',
                'ngay_sinh' => '1988-11-08',
                'cccd' => '456789123456',
                'dia_chi' => '789 Đường DEF, Quận 5, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B2',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-06-25',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 5200000
            ],
            [
                'ho' => 'Phạm',
                'ten' => 'Thị Dung',
                'ngay_sinh' => '1992-05-12',
                'cccd' => '789123456789',
                'dia_chi' => '321 Đường GHI, Quận 7, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B1',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-07-01',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 4800000
            ],
            [
                'ho' => 'Hoàng',
                'ten' => 'Văn Em',
                'ngay_sinh' => '1997-09-30',
                'cccd' => '321654987321',
                'dia_chi' => '654 Đường JKL, Quận 10, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B2',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-07-05',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 5500000
            ],
            [
                'ho' => 'Vũ',
                'ten' => 'Thị Phương',
                'ngay_sinh' => '1993-12-03',
                'cccd' => '654987321654',
                'dia_chi' => '987 Đường MNO, Quận 2, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B1',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-07-10',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 4600000
            ],
            [
                'ho' => 'Đặng',
                'ten' => 'Văn Giang',
                'ngay_sinh' => '1989-04-18',
                'cccd' => '987321654987',
                'dia_chi' => '147 Đường PQR, Quận 4, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B2',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-07-15',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 5300000
            ],
            [
                'ho' => 'Bùi',
                'ten' => 'Thị Hoa',
                'ngay_sinh' => '1991-08-25',
                'cccd' => '321987654321',
                'dia_chi' => '258 Đường STU, Quận 6, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B1',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-07-20',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 4700000
            ],
            [
                'ho' => 'Ngô',
                'ten' => 'Văn Inh',
                'ngay_sinh' => '1994-01-07',
                'cccd' => '654321987654',
                'dia_chi' => '369 Đường VWX, Quận 8, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B2',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-07-25',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 5400000
            ],
            [
                'ho' => 'Lý',
                'ten' => 'Thị Khoa',
                'ngay_sinh' => '1996-06-14',
                'cccd' => '987654321987',
                'dia_chi' => '741 Đường YZA, Quận 9, TP.HCM',
                'khoa_hoc' => 'Lái xe ô tô B1',
                'noi_dung_thi' => 'Lý thuyết + Thực hành',
                'ngay_sat_hach' => '2024-08-01',
                'dau_moi' => 'Đạt',
                'ly_thuyet' => 'Đạt',
                'mo_phong' => 'Đạt',
                'thuc_hanh' => 'Đạt',
                'duong_truong' => 'Đạt',
                'hoc_phi' => 4900000
            ]
        ];

        foreach ($hocViens as $hocVien) {
            hocVien::create($hocVien);
        }
    }
} 