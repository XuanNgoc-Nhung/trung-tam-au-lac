<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\nguoiDung;
use App\Models\hocVien;
use Maatwebsite\Excel\Facades\Excel;
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
            $hoc_vien = hocVien::where('ho', 'like', '%' . $request->keyword . '%')
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
        
        $check = hocPhi::where('sbd', $request->sbd)->where('ngay_thi', $request->ngay_thi)->first();
        return view('dang-ky-3', compact(['check', 'cauHinh']));
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
                'ngay_thi' => $ngayThi
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
                $formattedData = [
                    'ho' => $item[2] ?? null,
                    'ten' => $item[3] ?? null,
                    'ngay_sinh' => $item[4] ?? null,
                    'cccd' => $item[5] ?? null,
                    'dia_chi' => $item[6] ?? null,
                    'khoa_hoc' => $item[7] ?? null,
                    'noi_dung_thi' => $item[8] ?? null,
                    'ngay_sat_hach' => $item[9] ?? null,
                    'dau_moi' => $item[10] ?? null,
                    'ly_thuyet' => $item[11] ?? null,
                    'mo_phong' => $item[12] ?? null,
                    'thuc_hanh' => $item[13] ?? null,
                    'duong_truong' => $item[14] ?? null
                ];
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
} 
