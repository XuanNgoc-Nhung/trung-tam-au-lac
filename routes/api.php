<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThanhToanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route cho thanh toán thành công - có thể gọi từ bên ngoài
Route::post('/thanh-toan-thanh-cong', [ThanhToanController::class, 'thanhToanThanhCong'])->name('api.thanh-toan-thanh-cong');

// Route test để kiểm tra API hoạt động
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
}); 