<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hocVien;
use App\Models\hocPhi;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\SatHach;
use App\Models\DauMoi;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function dauMoi()
    {
        $dauMois = DauMoi::all();
        return view('dau-moi', compact('dauMois'));
    }

    // Thêm đầu mối mới
    public function storeDauMoi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ma_dau_moi' => 'required|string|max:50|unique:dau_moi,ma_dau_moi',
            'ten_dau_moi' => 'required|string|max:255',
            'so_luong_thi_sinh' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ]);
        }

        try {
            DauMoi::create([
                'ma_dau_moi' => $request->ma_dau_moi,
                'ten_dau_moi' => $request->ten_dau_moi,
                'so_luong_thi_sinh' => $request->so_luong_thi_sinh,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm đầu mối thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm đầu mối: ' . $e->getMessage()
            ]);
        }
    }

    // Lấy thông tin đầu mối để edit
    public function editDauMoi($ma_dau_moi)
    {
        $dauMoi = DauMoi::where('ma_dau_moi', $ma_dau_moi)->first();
        
        if (!$dauMoi) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đầu mối'
            ]);
        }
        //cập nhât các đầu mối ở bảng sát hạch
        $satsHach = SatHach::where('dau_moi', $ma_dau_moi)->get();
        foreach ($satsHach as $satsHach) {
            $satsHach->dau_moi = $ma_dau_moi;
            $satsHach->save();
        }   

        return response()->json([
            'success' => true,
            'data' => $dauMoi
        ]);
    }

    // Cập nhật đầu mối
    public function updateDauMoi(Request $request, $ma_dau_moi)
    {
        $validator = Validator::make($request->all(), [
            'ma_dau_moi' => 'required|string|max:50|unique:dau_moi,ma_dau_moi,' . $ma_dau_moi . ',ma_dau_moi',
            'ten_dau_moi' => 'required|string|max:255',
            'so_luong_thi_sinh' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $dauMoi = DauMoi::where('ma_dau_moi', $ma_dau_moi)->first();
            
            if (!$dauMoi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đầu mối'
                ]);
            }
            //cập nhât các đầu mối ở bảng sát hạch
            $satsHach = SatHach::where('dau_moi', $ma_dau_moi)->get();
            foreach ($satsHach as $satsHach) {
                $satsHach->dau_moi = $request->ma_dau_moi;
                $satsHach->save();
            }
            //cập nhât các đầu mối ở bảng học viên
            $hocVien = hocVien::where('dau_moi', $ma_dau_moi)->get();
            foreach ($hocVien as $hocVien) {
                $hocVien->dau_moi = $request->ma_dau_moi;
                $hocVien->save();
            }

            $dauMoi->update([
                'ma_dau_moi' => $request->ma_dau_moi,
                'ten_dau_moi' => $request->ten_dau_moi,
                'so_luong_thi_sinh' => $request->so_luong_thi_sinh,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật đầu mối thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật đầu mối: ' . $e->getMessage()
            ]);
        }
    }

    // Xóa đầu mối
    public function destroyDauMoi($ma_dau_moi)
    {
        try {
            $dauMoi = DauMoi::where('ma_dau_moi', $ma_dau_moi)->first();
            
            if (!$dauMoi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đầu mối'
                ]);
            }

            // Kiểm tra xem có học viên nào đang sử dụng đầu mối này không
            $hocVienCount = hocVien::where('dau_moi', $ma_dau_moi)->count();
            if ($hocVienCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa đầu mối này vì có ' . $hocVienCount . ' học viên đang sử dụng'
                ]);
            }

            $dauMoi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa đầu mối thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa đầu mối: ' . $e->getMessage()
            ]);
        }
    }

    // Hiển thị danh sách học viên
    public function index(Request $request)
    {
        $query = hocVien::query();
        // Lọc theo từ khóa (CCCD hoặc tên hoặc họ)
        if ($request->filled('tu_khoa')) {
            $tuKhoa = $request->tu_khoa;
            $query->where(function($q) use ($tuKhoa) {
                $q->where('cccd', 'like', "%$tuKhoa%")
                  ->orWhere('ten', 'like', "%$tuKhoa%")
                  ->orWhere('ho', 'like', "%$tuKhoa%") ;
            });
        }
        $hocViens = $query->get();
        return view('nhap-hoc-vien', compact('hocViens'));
    }

    // Lấy thông tin học viên để edit
    public function edit($id)
    {
        $hocVien = hocVien::findOrFail($id);
        return response()->json($hocVien);
    }

    // Cập nhật học viên
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'ngay_sinh' => 'required|date',
            'dia_chi' => 'required|string',
            'khoa_hoc' => 'required|string|max:255',
            'dau_moi' => 'required|string|max:255',
            'ly_thuyet' => 'required|integer|min:0|max:100',
            'mo_phong' => 'required|integer|min:0|max:100',
            'thuc_hanh' => 'required|integer|min:0|max:100',
            'duong_truong' => 'required|integer|min:0|max:100',
            'hoc_phi' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra đầu mối có tồn tại không
        $dauMoiExists = \App\Models\DauMoi::where('ma_dau_moi', $request->dau_moi)->exists();
        if (!$dauMoiExists) {
            return redirect()->back()
                ->withErrors(['dau_moi' => 'Đầu mối "' . $request->dau_moi . '" không tồn tại trong hệ thống. Vui lòng chọn đầu mối khác hoặc liên hệ quản trị viên để thêm đầu mối mới.'])
                ->withInput();
        }

        $hocVien = \App\Models\hocVien::findOrFail($id);
        
        // Cập nhật tất cả trường trừ CCCD
        $hocVien->update([
            'ho' => $request->ho,
            'ten' => $request->ten,
            'ngay_sinh' => $request->ngay_sinh,
            'dia_chi' => $request->dia_chi,
            'khoa_hoc' => $request->khoa_hoc,
            'dau_moi' => $request->dau_moi,
            'ly_thuyet' => $request->ly_thuyet,
            'mo_phong' => $request->mo_phong,
            'thuc_hanh' => $request->thuc_hanh,
            'duong_truong' => $request->duong_truong,
            'hoc_phi' => $request->hoc_phi,
        ]);

        return redirect()->back()->with('success', 'Cập nhật học viên thành công!');
    }

    // Xóa học viên
    public function destroy($id)
    {
        $hocVien = hocVien::findOrFail($id);
        $hocVien->delete();

        return response()->json(['success' => true]);
    }

    public function importFromJson(Request $request)
    {
        
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ'
                ]);
            }

            $data = $request->input('data');
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                try {
                    $hocPhiData = [
                        'sbd' => $row['sbd'],
                        'ho_va_ten' => $row['ho_va_ten'],
                        'so_cccd' => $row['cccd'],
                        'ngay_sinh' => $this->parseDate($row['ngay_sinh']),
                        'dia_chi' => $row['dia_chi'],
                        'hang' => $row['khoa_hoc'],
                        'dau_moi' => $row['dau_moi'],
                        'le_phi' => $row['hoc_phi'],
                        'trang_thai' => $row['trang_thai'] ?? 'Chưa thanh toán',
                    ];
                    $validator = Validator::make($hocPhiData, [
                        'sbd' => 'nullable|string|max:50',
                        'ho_va_ten' => 'required|string|max:255',
                        'so_cccd' => 'required|string|max:20|unique:hoc_phi,so_cccd',
                        'ngay_sinh' => 'required|date',
                        'dia_chi' => 'required|string',
                        'hang' => 'required|string|max:50',
                        'dau_moi' => 'required|string|max:255',
                        'le_phi' => 'required|numeric|min:0',
                        'trang_thai' => 'nullable|string|max:100',
                    ]);

                    if ($validator->fails()) {
                        $errorCount++;
                        $errors[] = "Dòng " . ($row['row_number'] ?? ($index + 1)) . ": " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    \App\Models\hocPhi::create($hocPhiData);
                    $successCount++;

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Dòng " . ($row['row_number'] ?? ($index + 1)) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'total' => count($data),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi import dữ liệu: ' . $e->getMessage()
            ]);
        }
    }

    // Import dữ liệu từ JSON
    public function importFromJsonBak(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ'
                ]);
            }

            $data = $request->input('data');
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                try {
                    $hocVienData = [
                        'ho' => $row['ho'],
                        'ten' => $row['ten'],
                        'ngay_sinh' => $this->parseDate($row['ngay_sinh']),
                        'cccd' => $row['cccd'],
                        'dia_chi' => $row['dia_chi'],
                        'khoa_hoc' => $row['khoa_hoc'],
                        'dau_moi' => $row['dau_moi'],
                        'ly_thuyet' => intval($row['ly_thuyet']),
                        'mo_phong' => intval($row['mo_phong']),
                        'thuc_hanh' => intval($row['thuc_hanh']),
                        'duong_truong' => intval($row['duong_truong']),
                        'hoc_phi' => intval($row['hoc_phi']),
                    ];

                    // Kiểm tra đầu mối có tồn tại không
                    $dauMoiExists = \App\Models\DauMoi::where('ma_dau_moi', $hocVienData['dau_moi'])->exists();
                    if (!$dauMoiExists) {
                        $errorCount++;
                        $errors[] = "Dòng " . ($row['row_number'] ?? ($index + 1)) . ": Đầu mối '" . $hocVienData['dau_moi'] . "' không tồn tại trong hệ thống";
                        continue;
                    }

                    $validator = Validator::make($hocVienData, [
                        'ho' => 'required|string|max:255',
                        'ten' => 'required|string|max:255',
                        'ngay_sinh' => 'required|date',
                        'cccd' => 'required|string|max:20|unique:hoc_vien,cccd',
                        'dia_chi' => 'required|string',
                        'khoa_hoc' => 'required|string|max:255',
                        'dau_moi' => 'required|string|max:255',
                        'ly_thuyet' => 'required|integer|min:0|max:100',
                        'mo_phong' => 'required|integer|min:0|max:100',
                        'thuc_hanh' => 'required|integer|min:0|max:100',
                        'duong_truong' => 'required|integer|min:0|max:100',
                        'hoc_phi' => 'required|integer|min:0',
                    ]);

                    if ($validator->fails()) {
                        $errorCount++;
                        $errors[] = "Dòng " . ($row['row_number'] ?? ($index + 1)) . ": " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    hocVien::create($hocVienData);
                    $successCount++;

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Dòng " . ($row['row_number'] ?? ($index + 1)) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'total' => count($data),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi import dữ liệu: ' . $e->getMessage()
            ]);
        }
    }

    // Tải mẫu Excel
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="mau_import_hoc_vien.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Tiêu đề
            fputcsv($file, [
                'Họ', 'Tên', 'Ngày sinh', 'CCCD', 'Địa chỉ', 'Hạng', 
                'Đầu mối', 'Lý thuyết', 'Mô phỏng', 'Thực hành', 'Đường trường', 'Lệ phí'
            ]);
            
            // Dữ liệu mẫu
            fputcsv($file, [
                'Nguyễn', 'Văn A', '01/01/1990', '123456789012', 'Hà Nội', 'B2',
                'Trung tâm A', '85', '90', '88', '92', '5000000'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Parse date từ Excel
    private function parseDate($dateString)
    {
        if (empty($dateString)) return null;
        
        // Thử parse các định dạng khác nhau
        $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y'];
        
        foreach ($formats as $format) {
            $date = Carbon::createFromFormat($format, $dateString);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }
        
        // Nếu không parse được, trả về null
        return null;
    }

    // Cập nhật trạng thái thanh toán
    public function updateTrangThai(Request $request, $id)
    {
        try {
            $hocPhi = hocPhi::find($id);
            
            if (!$hocPhi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy học viên'
                ], 404);
            }

            $daThanhToan = $request->input('da_thanh_toan', false);
            
            // Cập nhật trạng thái thanh toán
            $hocPhi->trang_thai = $daThanhToan ? 'Đã thanh toán' : 'Chưa thanh toán';
            $hocPhi->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thanh toán thành công',
                'data' => [
                    'id' => $hocPhi->id,
                    'trang_thai' => $hocPhi->trang_thai
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }
}
