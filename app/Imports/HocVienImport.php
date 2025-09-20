<?php

namespace App\Imports;

use App\Models\hocVien;
use App\Models\DauMoi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class HocVienImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, WithProgressBar
{
    use Importable, SkipsErrors;
    
    private $importedCount = 0;

    public function model(array $row)
    {
        // Chuyển đổi ngày sinh từ dd/mm/yyyy sang yyyy-mm-dd
        $ngaySinh = null;
        if (!empty($row['ngay_sinh'])) {
            try {
                $ngaySinh = Carbon::createFromFormat('d/m/Y', $row['ngay_sinh'])->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $ngaySinh = Carbon::createFromFormat('Y-m-d', $row['ngay_sinh'])->format('Y-m-d');
                } catch (\Exception $e2) {
                    $ngaySinh = null;
                }
            }
        }

        // Giữ nguyên ngày sát hạch dưới dạng string
        $ngaySatHach = $row['ngay_sat_hach'] ?? null;

        // Tìm đầu mối theo tên hoặc mã
        $dauMoiId = null;
        if (!empty($row['dau_moi'])) {
            $dauMoi = DauMoi::where('ten_dau_moi', $row['dau_moi'])
                           ->orWhere('ma_dau_moi', $row['dau_moi'])
                           ->first();
            if ($dauMoi) {
                $dauMoiId = $dauMoi->ma_dau_moi;
            }
        }

        $this->importedCount++;
        
        return new hocVien([
            'ho' => $row['ho'] ?? '',
            'ten' => $row['ten'] ?? '',
            'ngay_sinh' => $ngaySinh,
            'cccd' => $row['cccd'] ?? '',
            'dia_chi' => $row['dia_chi'] ?? '',
            'khoa_hoc' => $row['khoa_hoc'] ?? '',
            'noi_dung_thi' => $row['noi_dung_thi'] ?? '',
            'ngay_sat_hach' => $ngaySatHach,
            'dau_moi' => $dauMoiId,
            'ghi_chu' => $row['ghi_chu'] ?? '',
            'ly_thuyet' => $row['ly_thuyet'] ?? null,
            'mo_phong' => $row['mo_phong'] ?? null,
            'thuc_hanh' => $row['thuc_hanh'] ?? null,
            'duong_truong' => $row['duong_truong'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.ho' => 'required|string|max:255',
            '*.ten' => 'required|string|max:255',
            '*.cccd' => 'required|string|max:20|unique:hoc_vien,cccd',
            '*.ngay_sinh' => 'nullable|date',
            '*.dia_chi' => 'nullable|string|max:500',
            '*.khoa_hoc' => 'nullable|string|max:255',
            '*.noi_dung_thi' => 'nullable|string|max:255',
            '*.ngay_sat_hach' => 'nullable|string|max:255',
            '*.dau_moi' => 'nullable|string|max:255',
            '*.ghi_chu' => 'nullable|string|max:1000',
            '*.ly_thuyet' => 'nullable|numeric|min:0|max:100',
            '*.mo_phong' => 'nullable|numeric|min:0|max:100',
            '*.thuc_hanh' => 'nullable|numeric|min:0|max:100',
            '*.duong_truong' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ho.required' => 'Họ không được để trống',
            'ten.required' => 'Tên không được để trống',
            'cccd.required' => 'CCCD không được để trống',
            'cccd.unique' => 'CCCD đã tồn tại trong hệ thống',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng',
            'ngay_sat_hach.string' => 'Ngày sát hạch phải là chuỗi ký tự',
            'ly_thuyet.numeric' => 'Điểm lý thuyết phải là số',
            'mo_phong.numeric' => 'Điểm mô phỏng phải là số',
            'thuc_hanh.numeric' => 'Điểm thực hành phải là số',
            'duong_truong.numeric' => 'Điểm đường trường phải là số',
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
    
    public function getRowCount(): int
    {
        return $this->importedCount;
    }
}
