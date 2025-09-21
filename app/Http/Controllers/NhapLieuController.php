<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\nguoiDung;
use App\Models\hocVien;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HocPhiTemplateExport;
use App\Exports\HocVienTemplateExport;
use App\Imports\HocVienImport;
use App\Models\hocPhan;
use App\Models\CauHinh;
use App\Models\DauMoi;
use App\Models\SatHach;
use App\Models\hocPhi;
use Illuminate\Support\Facades\Log;
class NhapLieuController extends Controller
{
    public function nhapLieu(Request $request)
    {
        $query = hocVien::query();
        // Lọc theo CCCD
        if ($request->has('tu_khoa') && !empty($request->tu_khoa)) {
            $query->where('cccd', 'like', '%' . $request->tu_khoa . '%')
                ->orWhere('ho', 'like', '%' . $request->tu_khoa . '%')
                ->orWhere('ten', 'like', '%' . $request->tu_khoa . '%');
        }
        $hocViens = $query->orderBy('created_at', 'desc')->get();
        return view('nhap-hoc-vien-2', compact('hocViens'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $path = $request->file('excel_file')->getRealPath();
        $data = Excel::toArray([], $path);
        $data = $data[0] ?? [];
        
        \Log::info('Excel data:', ['data' => $data]);

        return view('nhap-lieu', compact('data'));
    }
    public function satHach(Request $request)
    {
        $query = SatHach::with('hocVien');
        
        // Apply search filters if they exist
        if ($request->has('keyword') && !empty($request->keyword)) {
            $hoc_vien = SatHach::where('ho', 'like', '%' . $request->keyword . '%')
                ->orWhere('ten', 'like', '%' . $request->keyword . '%')
                ->orWhere('cccd', 'like', '%' . $request->keyword . '%')
                ->pluck('cccd');
            
            $query->where('cccd', 'like', '%' . $request->keyword . '%')
                ->orWhereIn('cccd', $hoc_vien)
                ->orWhere('ghi_chu', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('ngay_thi') && !empty($request->ngay_thi)) {
            $query->where('ngay_thi', $request->ngay_thi);
        }

        // Filter by ngay_sat_hach
        if ($request->has('ngay_sat_hach') && !empty($request->ngay_sat_hach)) {
            $query->whereHas('hocVien', function($q) use ($request) {
                $q->where('ngay_sat_hach', $request->ngay_sat_hach);
            });
        }
        
        $satHaches = $query->orderBy('created_at', 'desc')->get();
        
        return view('sat-hach', compact('satHaches'));
    }

    public function exportSatHach(Request $request)
    {
        $query = SatHach::with('hocVien');
        
        // Apply search filters if they exist
        if ($request->has('keyword') && !empty($request->keyword)) {
            $hoc_vien = hocVien::where('ho', 'like', '%' . $request->keyword . '%')
                ->orWhere('ten', 'like', '%' . $request->keyword . '%')
                ->orWhere('cccd', 'like', '%' . $request->keyword . '%')
                ->pluck('cccd');
            
            $query->where('cccd', 'like', '%' . $request->keyword . '%')
                ->orWhereIn('cccd', $hoc_vien);
        }

        if ($request->has('ngay_thi') && !empty($request->ngay_thi)) {
            $query->where('ngay_thi', $request->ngay_thi);
        }

        // Filter by ngay_sat_hach
        if ($request->has('ngay_sat_hach') && !empty($request->ngay_sat_hach)) {
            $query->whereHas('hocVien', function($q) use ($request) {
                $q->where('ngay_sat_hach', $request->ngay_sat_hach);
            });
        }
        
        $satHaches = $query->orderBy('created_at', 'desc')->get();
        
        $fileName = 'danh_sach_sat_hach_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new \App\Exports\SatHachExport($satHaches), $fileName);
    }
    public function admin(Request $request)
    {
        $hocViens = hocVien::orderBy('created_at', 'desc')->get();
        return view('layouts.app', compact('hocViens'));
    }
    public function danhSachHocVien(Request $request)
    {
        $query = SatHach::with('hocVien');
        $hoc_vien = hocVien::where('ho', 'like', '%' . $request->keyword . '%')
        ->orWhere('ten', 'like', '%' . $request->keyword . '%')
        ->orWhere('cccd', 'like', '%' . $request->keyword . '%')
        ->pluck('cccd');
        // Apply search filters if they exist
        if ($request->has('keyword') && !empty($request->keyword)) {
            $query->where('cccd', 'like', '%' . $request->keyword . '%')
            ->orWhere('cccd', 'like', '%' . $request->keyword . '%')
            ->orWhereIn('cccd', $hoc_vien);
        }

        if ($request->has('ngay_thi') && !empty($request->ngay_thi)) {
            $query->where('ngay_thi', $request->ngay_thi);
        }
        $satHaches = $query->get();
        return view('danh-sach-hoc-vien', compact('satHaches'));
    }
    public function login(Request $request)
    {
        return view('login');
    }
    
    public function processLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        // Kiểm tra thông tin đăng nhập admin
        // Bạn có thể thay đổi logic này theo yêu cầu của mình
        if ($request->username === 'admin' && $request->password === 'admin123') {
            // Lưu session đăng nhập
            session(['admin_logged_in' => true]);
            session(['user_role' => 'admin']);
            
            return redirect()->route('admin')->with('success', 'Đăng nhập thành công!');
        }
        
        return redirect()->route('login')->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng!');
    }
    
    public function logout()
    {
        // Xóa session đăng nhập
        session()->forget(['admin_logged_in', 'user_role']);
        
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
    public function dangKy(Request $request)
    {
        $cauHinh = CauHinh::first();
        if (!$cauHinh) {
            return redirect()->route('home');
        }
        $ngayThi = $cauHinh->ngay_thi;
        $danh_sach_dau_moi = DauMoi::all();
        $dau_moi_id = DauMoi::pluck('ma_dau_moi');
        
        // Lọc học viên theo đầu mối nếu có tham số dau_moi trong URL
        if ($request->has('dau_moi') && !empty($request->dau_moi)) {
            $danh_sach_hoc_vien = hocVien::where('dau_moi', $request->dau_moi)->get();
        } else {
            $danh_sach_hoc_vien = hocVien::whereIn('dau_moi', $dau_moi_id)->get();
        }
        $check = null;
        if ($request->has('cccd') && !empty($request->cccd)) {
            $check = hocVien::where('cccd', $request->cccd)->where('ngay_sat_hach', $ngayThi)->first();
        } else {
            $check = hocVien::where('cccd', $request->cccd)->where('ngay_sat_hach', $ngayThi)->first();
        }
        // Lấy danh sách học viên để hiển thị trong selectbox
        
        return view('dang-ky-2', compact(['check', 'cauHinh', 'ngayThi', 'danh_sach_dau_moi', 'danh_sach_hoc_vien']));
    }
    public function luuCauHinh(Request $request)
    {
        \Log::info('Lưu cấu hình:', ['data' => $request->all()]);
        //kiểm tra xem có dữ liệu trong bảng cau_hinh chưa
        $cauHinh = CauHinh::first();
        
        if ($cauHinh) {
            Log::info('Updating cauHinh:', ['ngan_hang' => $request->ngan_hang]);
            $cauHinh->ngan_hang = $request->ngan_hang;
            $cauHinh->so_tai_khoan = $request->so_tai_khoan;
            $cauHinh->chu_tai_khoan = $request->chu_tai_khoan;
            $cauHinh->ngay_thi = $request->ngay_thi;    
            $cauHinh->save();
            return redirect()->route('cau-hinh.index')->with('success', 'Cập nhật cấu hình thành công!');
        } else {
            $cauHinh = new CauHinh();
            Log::info('Creating new cauHinh:', ['ngan_hang' => $request->ngan_hang]);
            $cauHinh->ngan_hang = $request->ngan_hang;
            $cauHinh->so_tai_khoan = $request->so_tai_khoan;
            $cauHinh->chu_tai_khoan = $request->chu_tai_khoan;
            $cauHinh->ngay_thi = $request->ngay_thi;
            $cauHinh->save();
            return redirect()->route('cau-hinh.index')->with('success', 'Tạo cấu hình thành công!');
        }
    }
    public function cauHinh(Request $request)
    {
        $cauHinh = CauHinh::first();
        \Log::info('cauHinh data:', ['cauHinh' => $cauHinh]);
        
        // Format ngày thi về dd/mm/yyyy nếu có dữ liệu
        // if ($cauHinh && $cauHinh->ngay_thi) {
        //     $cauHinh->ngay_thi_formatted = \Carbon\Carbon::parse($cauHinh->ngay_thi)->format('d/m/Y');
        //     \Log::info('Formatted ngay_thi:', ['formatted' => $cauHinh->ngay_thi_formatted]);
        // }
        
        return view('cau-hinh', compact('cauHinh'));
    }
    public function dangKyHocPhan(Request $request)
    {
        try {
            $data = $request->all();
            \Log::info('Data received for hocPhan:', ['data' => $data]);

            // Kiểm tra số lượng người đã đăng ký trong cùng thời gian
            $count = 1;
            $cauHinh = CauHinh::first();
            $soLuongThiSinh = $cauHinh->so_luong_thi_sinh;
            $cccd = $data['cccd'];
            $hocVien = hocVien::where('cccd', $cccd)->first();
            $dauMoi = $hocVien->dau_moi;
            $chiTietDauMoi = DauMoi::where('ma_dau_moi', $dauMoi)->first();
            if(!$chiTietDauMoi){
                return response()->json(['rc' => 1, 'message' => 'Đầu mối chưa được khai báo. Vui lòng liên hệ quản trị viên.']);
            }
            $soLuongThiSinh = $chiTietDauMoi->so_luong_thi_sinh;
            $soLuongThiSinhHienCo = SatHach::where('dau_moi', $dauMoi)->count();
            if($soLuongThiSinhHienCo >= $soLuongThiSinh){
                return response()->json(['rc' => 1, 'message' => 'Đã đủ số lượng thí sinh đăng ký']);
            }
            // Kiểm tra người dùng đã đăng ký trong thời gian này chưa
            $existingRegistration = SatHach::where('cccd', $data['cccd'])
                                         ->first();
            
            if ($existingRegistration) {
                return response()->json(['rc' => 1, 'message' => 'Học viên đã được đăng ký thi.']);
            }
            $cauHinh = CauHinh::first();
            $ngayThi = $cauHinh->ngay_thi;
            $dataCreate = [
                'cccd' => $data['cccd'],
                'ngay_thi' => $ngayThi,
                'dau_moi' => $hocVien->dau_moi,
            ];
            $satHach = SatHach::create($dataCreate);
            \Log::info('SatHach created successfully:', ['satHach' => $satHach]);
            return response()->json(['rc' => 0, 'message' => 'Đăng ký thành công']);
        } catch (\Exception $e) {
            \Log::error('Error creating satHach: ' . $e->getMessage());
            return response()->json(['rc' => 1, 'message' => 'Đăng ký thất bại: ' . $e->getMessage()]);
        }
    }
    public function traCuu(Request $request)
    {
        $satHaches = SatHach::where('cccd', $request->cccd)->with('hocVien')->get();
        return view('tra-cuu', compact('satHaches'));
    }
    public function checkAvailableSlots(Request $request)
    {
        $data = $request->all();
        $ngayHoc = $data['ngay_hoc'];

        // Danh sách các khung giờ cần kiểm tra
        $timeSlots = ['7:30', '8:30', '9:30', '10:30', '13:30', '14:30', '15:30', '16:30'];
        
        $result = [];
        foreach ($timeSlots as $slot) {
            $count = hocPhan::where('ngay_hoc', $ngayHoc)
                           ->where('gio_hoc', $slot)
                           ->count();
            
            $result[] = [
                'gio_hoc' => $slot,
                'total' => $count
            ];
        }

        return response()->json(['rc' => 0, 'data' => $result]);
    }
    
    public function uploadJson(Request $request)
    {
        try {
            $data = $request->all();
            $data = $data['data'];
            \Log::info('JSON data from request:', ['data' => $data]);

            // Format và chèn từng bản ghi vào database
            foreach ($data as $item) {
                // Kiểm tra xem item có phải là object với key là string (từ nhap-lieu-2.blade.php) hay không
                if (isset($item['ho']) || isset($item['ten'])) {
                    // Format từ nhap-lieu-2.blade.php (object với key string)
                    $formattedData = [
                        'ho' => $item['ho'] ?? null,
                        'ten' => $item['ten'] ?? null,
                        'ngay_sinh' => $item['ngay_sinh'] ?? null,
                        'cccd' => $item['cccd'] ?? null,
                        'dia_chi' => $item['dia_chi'] ?? null,
                        'khoa_hoc' => $item['khoa_hoc'] ?? null,
                        'noi_dung_thi' => $item['noi_dung_thi'] ?? null,
                        'ngay_sat_hach' => $item['ngay_sat_hach'] ?? null,
                        'dau_moi' => $item['dau_moi'] ?? null,
                        'ly_thuyet' => $item['ly_thuyet'] ?? null,
                        'mo_phong' => $item['mo_phong'] ?? null,
                        'thuc_hanh' => $item['thuc_hanh'] ?? null,
                        'duong_truong' => $item['duong_truong'] ?? null
                    ];
                } else {
                    // Format từ nhap-lieu.blade.php (object với key là số)
                    $formattedData = [
                        'ho' => $item['2'] ?? null,
                        'ten' => $item['3'] ?? null,
                        'ngay_sinh' => $item['4'] ?? null,
                        'cccd' => $item['5'] ?? null,
                        'dia_chi' => $item['6'] ?? null,
                        'khoa_hoc' => $item['7'] ?? null,
                        'noi_dung_thi' => $item['8'] ?? null,
                        'ngay_sat_hach' => $item['9'] ?? null,
                        'dau_moi' => $item['10'] ?? null,
                        'ly_thuyet' => $item['11'] ?? null,
                        'mo_phong' => $item['12'] ?? null,
                        'thuc_hanh' => $item['13'] ?? null,
                        'duong_truong' => $item['14'] ?? null
                    ];
                }
                hocVien::create($formattedData);
            }
            
            return response()->json(['rc' => 0, 'message' => 'Import thành công']);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi import dữ liệu: ' . $e->getMessage());
            return response()->json(['rc' => 1, 'message' => 'Import thất bại: ' . $e->getMessage()]);
        }
    }
    public function capNhatHocVien(Request $request)
    {
        try {
            \Log::info('Data received for capNhatHocVien:', ['data' => $request->all()]);
            $data = $request->all();
            $hocPhan = hocPhan::find($data['id']);
            $hocPhan->trang_thai = $data['trang_thai'];
            $hocPhan->ngay_hoc = $data['ngay_hoc'];
            $hocPhan->gio_hoc = $data['gio_hoc'];
            $hocPhan->giao_vien = $data['giao_vien'];
            $hocPhan->save();   
            return redirect()->route('danh-sach-hoc-vien');
        } catch (\Exception $e) {
            \Log::error('Lỗi khi cập nhật dữ liệu: ' . $e->getMessage());
            return redirect()->route('danh-sach-hoc-vien')->with('error', 'Cập nhật thất bại: ' . $e->getMessage());
        }
    }
    public function xoaHocVien(Request $request)
    {
        $data = $request->all();
        $hocPhan = hocPhan::find($data['id']);
        $hocPhan->delete();
        return response()->json(['rc' => 0, 'message' => 'Xóa thành công']);
    }
    public function capNhatTrangThai($id, Request $request)
    {
        \Log::info('Data received for capNhatTrangThai:', ['data' => $request->all()]);
        \Log::info('ID:', ['id' => $id]);
        $hocPhan = hocPhan::find($id);
        if ($hocPhan) {
            $hocPhan->trang_thai = $request->trang_thai;
            $hocPhan->save();
            return response()->json(['rc' => 0, 'message' => 'Cập nhật thành công']);
        } else {
            return response()->json(['rc' => 1, 'message' => 'Không tìm thấy học phần']);
        }
    }
    public function getHocVienInfo(Request $request)
    {
        try {
            $cccd = $request->input('cccd');
            
            \Log::info('=== API GET HỌC VIÊN INFO ===');
            \Log::info('CCCD được yêu cầu:', ['cccd' => $cccd]);
            
            if (!$cccd) {
                \Log::warning('CCCD không được cung cấp');
                return response()->json(['rc' => 1, 'message' => 'CCCD không được để trống']);
            }
            
            // Tìm học viên trong bảng hocPhi
            $hocVien = hocVien::where('cccd', $cccd)->first();
            
            if (!$hocVien) {
                \Log::warning('Không tìm thấy học viên với CCCD:', ['cccd' => $cccd]);
                return response()->json(['rc' => 1, 'message' => 'Không tìm thấy học viên']);
            }
            
            \Log::info('Tìm thấy học viên:', [
                'cccd' => $hocVien->cccd,
                'ho_va_ten' => $hocVien->ho . ' ' . $hocVien->ten,
                'ngay_sinh' => $hocVien->ngay_sinh,
                'khoa_hoc' => $hocVien->khoa_hoc,
                'dau_moi' => $hocVien->dau_moi
            ]);
            
            // Lấy thông tin đầu mối
            $dauMoi = DauMoi::where('ma_dau_moi', $hocVien->dau_moi)->first();
            $tenDauMoi = $dauMoi ? $dauMoi->ten_dau_moi : '';
            
            \Log::info('Thông tin đầu mối:', [
                'ma_dau_moi' => $hocVien->dau_moi,
                'ten_dau_moi' => $tenDauMoi
            ]);
            
            $data = [
                'cccd' => $hocVien->cccd,
                'ho_va_ten' => $hocVien->ho . ' ' . $hocVien->ten,
                'ngay_sinh' => $hocVien->ngay_sinh,
                'khoa_hoc' => $hocVien->khoa_hoc,
                'dau_moi' => $tenDauMoi
            ];
            
            \Log::info('Dữ liệu trả về cho client:', $data);
            \Log::info('=== KẾT THÚC API GET HỌC VIÊN INFO ===');
            
            return response()->json(['rc' => 0, 'data' => $data]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting hoc vien info: ' . $e->getMessage());
            return response()->json(['rc' => 1, 'message' => 'Có lỗi xảy ra khi lấy thông tin học viên']);
        }
    }

    public function hocPhi(Request $request)
    {
        $query = hocPhi::query();
        
        // Lọc theo từ khóa (CCCD hoặc họ và tên)
        if ($request->filled('tu_khoa')) {
            $tuKhoa = $request->tu_khoa;
            $query->where(function($q) use ($tuKhoa) {
                $q->where('so_cccd', 'like', "%$tuKhoa%")
                  ->orWhere('ho_va_ten', 'like', "%$tuKhoa%");
            });
        }
        if ($request->filled('sbd')) {
            $sbd = $request->sbd;
            $query->where('sbd', 'like', "%$sbd%");
        }
        if ($request->filled('ma_thanh_toan')) {
            $maThanhToan = $request->ma_thanh_toan;
            $maThanhToan = substr($maThanhToan, 3);
            $query->where('id', $maThanhToan);
        }
        if ($request->filled('ngay_thi')) {
            $ngayThi = $request->ngay_thi;
            $query->where('ngay_thi', $ngayThi);
        }
        // Lọc theo trạng thái thanh toán
        if ($request->filled('trang_thai')) {
            $trangThai = $request->trang_thai;
            
            switch ($trangThai) {
                case 'da_thanh_toan':
                    $query->where('trang_thai', 'Đã thanh toán');
                    break;
                    
                case 'chua_thanh_toan':
                    $query->where('trang_thai', 'Chưa thanh toán');
                    break;
            }
        }
        
        $hocViens = $query->get();
        return view('hoc-phi', compact('hocViens'));
    }

    // Tải mẫu Excel cho học phí
    public function downloadHocPhiTemplate()
    {
        return Excel::download(new HocPhiTemplateExport, 'mau_import_hoc_phi.xlsx');
    }

    // Tải mẫu Excel cho học viên
    public function downloadHocVienTemplate()
    {
        return Excel::download(new HocVienTemplateExport, 'mau_import_hoc_vien.xlsx');
    }

    // Import học viên từ Excel
    public function importHocVien(Request $request)
    {
        // Kiểm tra xem request có chứa JSON data không
        if ($request->has('data') && is_array($request->data)) {
            // Xử lý JSON data
            return $this->importHocVienFromJson($request);
        }
        
        // Xử lý file upload (giữ nguyên logic cũ)
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            $import = new HocVienImport();
            Excel::import($import, $request->file('excel_file'));
            
            $importedCount = $import->getRowCount();
            $errors = $import->errors();
            
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = "Dòng {$error->row()}: {$error->errors()[0]}";
                }
                
                return response()->json([
                    'rc' => 1, 
                    'message' => "Import thành công {$importedCount} học viên. Có " . count($errors) . " dòng bị lỗi: " . implode(', ', $errorMessages)
                ]);
            }
            
            return response()->json([
                'rc' => 0, 
                'message' => "Import thành công {$importedCount} học viên"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lỗi import học viên: ' . $e->getMessage());
            return response()->json([
                'rc' => 1, 
                'message' => 'Import thất bại: ' . $e->getMessage()
            ]);
        }
    }

    // Import học viên từ JSON data
    private function importHocVienFromJson(Request $request)
    {
        try {
            $data = $request->input('data');
            $total = count($data);
            $successCount = 0;
            $errorCount = 0;
            $duplicateCount = 0;
            $errors = [];

            \Log::info('Import JSON data:', ['total' => $total]);

            foreach ($data as $index => $item) {
                try {
                    // Kiểm tra dữ liệu bắt buộc
                    if (empty($item['ho']) || empty($item['ten']) || empty($item['cccd'])) {
                        $errors[] = "Dòng " . ($index + 1) . ": Thiếu thông tin bắt buộc (Họ, Tên, CCCD)";
                        $errorCount++;
                        continue;
                    }

                    // Kiểm tra CCCD đã tồn tại chưa
                    $existingHocVien = hocVien::where('cccd', $item['cccd'])->first();
                    if ($existingHocVien) {
                        $duplicateCount++;
                        continue; // Bỏ qua nếu đã tồn tại
                    }

                    // Tạo học viên mới
                    $hocVienData = [
                        'ho' => $item['ho'],
                        'ten' => $item['ten'],
                        'ngay_sinh' => $item['ngay_sinh'],
                        'cccd' => $item['cccd'],
                        'dia_chi' => $item['dia_chi'],
                        'khoa_hoc' => $item['khoa_hoc'],
                        'noi_dung_thi' => $item['noi_dung_thi'],
                        'ngay_sat_hach' => $item['ngay_sat_hach'],
                        'dau_moi' => $item['dau_moi'],
                        'ghi_chu' => $item['ghi_chu'],
                        'ly_thuyet' => (int)($item['ly_thuyet'] ?? 0),
                        'mo_phong' => (int)($item['mo_phong'] ?? 0),
                        'thuc_hanh' => (int)($item['thuc_hanh'] ?? 0),
                        'duong_truong' => (int)($item['duong_truong'] ?? 0),
                    ];

                    hocVien::create($hocVienData);
                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Dòng " . ($index + 1) . ": " . $e->getMessage();
                    $errorCount++;
                    \Log::error('Lỗi tạo học viên dòng ' . ($index + 1) . ': ' . $e->getMessage());
                }
            }

            \Log::info('Import kết quả:', [
                'total' => $total,
                'success' => $successCount,
                'error' => $errorCount,
                'duplicate' => $duplicateCount
            ]);

            return response()->json([
                'rc' => 0,
                'success' => true,
                'total' => $total,
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'duplicate_count' => $duplicateCount,
                'errors' => $errors,
                'message' => "Import hoàn thành: {$successCount} thành công, {$errorCount} lỗi, {$duplicateCount} trùng lặp"
            ]);

        } catch (\Exception $e) {
            \Log::error('Lỗi import JSON: ' . $e->getMessage());
            return response()->json([
                'rc' => 1,
                'success' => false,
                'message' => 'Import thất bại: ' . $e->getMessage()
            ]);
        }
    }
} 
