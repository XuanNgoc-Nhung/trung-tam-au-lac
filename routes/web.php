<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NhapLieuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ThanhToanController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Routes cho UserController (quản lý học viên)
Route::get('/hoc-vien', [UserController::class, 'index'])->name('hoc-vien.index');
Route::get('/dau-moi', [UserController::class, 'dauMoi'])->name('dau-moi.index');
Route::post('/dau-moi', [UserController::class, 'storeDauMoi'])->name('dau-moi.store');
Route::get('/dau-moi/{ma_dau_moi}/edit', [UserController::class, 'editDauMoi'])->name('dau-moi.edit');
Route::put('/dau-moi/{ma_dau_moi}', [UserController::class, 'updateDauMoi'])->name('dau-moi.update');
Route::delete('/dau-moi/{ma_dau_moi}', [UserController::class, 'destroyDauMoi'])->name('dau-moi.destroy');
Route::get('/hoc-vien/{id}/edit', [UserController::class, 'edit'])->name('hoc-vien.edit');
Route::put('/hoc-vien/{id}', [UserController::class, 'update'])->name('hoc-vien.update');
Route::delete('/hoc-vien/{id}', [UserController::class, 'destroy'])->name('hoc-vien.destroy');
Route::post('/hoc-vien/import-excel', [UserController::class, 'importExcel'])->name('hoc-vien.import-excel');
Route::post('/hoc-vien/import-json', [UserController::class, 'importFromJson'])->name('hoc-vien.import-json');
Route::get('/hoc-vien/download-template', [UserController::class, 'downloadTemplate'])->name('hoc-vien.download-template');
Route::get('/thanh-toan', [ThanhToanController::class, 'thanhToan'])->name('thanh-toan');

Route::post('/thanh-toan-thanh-cong', [ThanhToanController::class, 'thanhToanThanhCong'])->name('thanh-toan-thanh-cong');
Route::get('/kiem-tra-trang-thai-thanh-toan', [ThanhToanController::class, 'kiemTraTrangThaiThanhToan'])->name('kiem-tra-trang-thai-thanh-toan');
Route::post('/check-thanh-toan', [ThanhToanController::class, 'checkThanhToan'])->name('check-thanh-toan');
Route::get('/hoan-thanh', [ThanhToanController::class, 'hoanThanh'])->name('hoan-thanh');

// Routes cho NhapLieuController (giữ nguyên)
Route::get('/danh-sach-hoc-vien', [NhapLieuController::class, 'danhSachHocVien'])->name('danh-sach-hoc-vien');
Route::get('/le-phi', [NhapLieuController::class, 'dangKy'])->name('dang-ky');
Route::get('/dang-ky', [NhapLieuController::class, 'dangKy'])->name('dang-ky');
Route::get('/tra-cuu', [NhapLieuController::class, 'traCuu'])->name('tra-cuu');
Route::get('/nhap-lieu', [NhapLieuController::class, 'nhapLieu'])->name('nhap-lieu');
Route::get('/hoc-phan', [NhapLieuController::class, 'hocPhan'])->name('hoc-phan.index');
Route::get('/sat-hach', [NhapLieuController::class, 'satHach'])->name('sat-hach.index');
Route::get('/bao-cao', [NhapLieuController::class, 'baoCao'])->name('bao-cao');
Route::get('/login', [NhapLieuController::class, 'login'])->name('login');
Route::post('/login', [NhapLieuController::class, 'processLogin'])->name('login.process');
Route::get('/logout', [NhapLieuController::class, 'logout'])->name('logout');
Route::group(['prefix' => 'admin','middleware' => 'loginAdmin'], function () {
    Route::get('/', [NhapLieuController::class, 'admin'])->name('admin');
    Route::get('/cau-hinh', [NhapLieuController::class, 'cauHinh'])->name('cau-hinh.index');
Route::get('/hoc-phi', [NhapLieuController::class, 'hocPhi'])->name('hoc-phi.index');
Route::get('/hoc-phi/download-template', [NhapLieuController::class, 'downloadHocPhiTemplate'])->name('hoc-phi.download-template');
Route::get('/hoc-vien/download-template', [NhapLieuController::class, 'downloadHocVienTemplate'])->name('hoc-vien.download-template');
Route::post('/hoc-vien/import', [NhapLieuController::class, 'importHocVien'])->name('hoc-vien.import');
});

Route::get('/xac-nhan', function () {
    return view('xac-nhan');
});

Route::post('/nhap-lieu', [NhapLieuController::class, 'upload'])->name('nhap-lieu.upload');
Route::post('/luu-cau-hinh', [NhapLieuController::class, 'luuCauHinh'])->name('cau-hinh.store');
Route::post('/nhap-lieu-json', [NhapLieuController::class, 'uploadJson'])->name('nhap-lieu.uploadJson');
Route::post('/dang-ky-hoc-phan', [NhapLieuController::class, 'dangKyHocPhan'])->name('dang-ky-hoc-phan');
Route::post('/check-available-slots', [NhapLieuController::class, 'checkAvailableSlots'])->name('check-available-slots');
Route::post('/get-hoc-vien-info', [NhapLieuController::class, 'getHocVienInfo'])->name('get-hoc-vien-info');
Route::post('/cap-nhat-hoc-vien', [NhapLieuController::class, 'capNhatHocVien'])->name('cap-nhat-hoc-vien');
Route::post('/cap-nhat-trang-thai/{id}', [NhapLieuController::class, 'capNhatTrangThai'])->name('cap-nhat-trang-thai');
Route::post('/xoa-hoc-vien', [NhapLieuController::class, 'xoaHocVien'])->name('xoa-hoc-vien');

Route::post('/hoc-vien/{id}/update-trang-thai', [UserController::class, 'updateTrangThai'])->name('hoc-vien.update-trang-thai');   