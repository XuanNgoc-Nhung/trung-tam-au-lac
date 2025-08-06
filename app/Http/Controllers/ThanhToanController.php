<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Models\CauHinh;
use App\Models\GiaoDich;
use App\Models\SatHach;
use App\Models\hocPhi;
use App\Models\hocVien;
class ThanhToanController extends Controller
{
    public function hoanThanh()
    {
        return view('hoan-thanh');
    }

    public function thanhToan(Request $request)
    {
        $cccd = $request->query('cccd');
        $hocVien = hocPhi::where('so_cccd', $cccd)->first();
        if($hocVien){
            $soTien = $hocVien->le_phi;
        }else{
            $soTien = 5000;
        }
        $cauHinh = CauHinh::first();
        $noiDung = "DH{$cccd}";
        $qrCode = "https://qr.sepay.vn/img?acc={$cauHinh->so_tai_khoan}&bank={$cauHinh->ngan_hang}&amount={$soTien}&des={$noiDung}&template=TEMPLATE&download=DOWNLOAD";
        return view('thanh-toan', compact('cccd', 'cauHinh', 'soTien', 'qrCode'));
    }

    public function thanhToanThanhCong(Request $request)
    {
        // Log thông tin request để debug
        Log::info('Thanh toán thành công - Request data:');
        Log::info($request->all());
        $dataCreate = [
            'gateway' => $request->gateway,
            'transaction_date' => $request->transactionDate,
            'account_number' => $request->accountNumber,
            'sub_account' => $request->subAccount,
            'accumulated' => $request->accumulated,
            'code' => $request->code,
            'transaction_content' => $request->content,
            'reference_number' => $request->referenceCode,
        ];
        if($request->transferType == 'out'){
            $dataCreate['amount_out'] = $request->transferAmount;
            $dataCreate['amount_in'] = 0;
        }else{
            $dataCreate['amount_in'] = $request->transferAmount;
            $dataCreate['amount_out'] = 0;
            GiaoDich::create($dataCreate);
        }
        //trả về với code 200
        return response()->json([
            'code' => 200,
            'message' => 'Thanh toán thành công'
        ]);
        // Lấy thông tin từ request
    }
    public function checkThanhToan(Request $request)
    {
        Log::info('Check thanh toán - Request data:');
        Log::info($request->all());
        $cccd = $request->cccd;
        $hocVien = hocPhi::where('so_cccd', $cccd)->first();
        if($hocVien){
            if($hocVien->trang_thai == 'Đã thanh toán'){
            return response()->json([
                    'rc' => 0,
                    'message' => 'Đã thanh toán',
                    'data' => $hocVien
                ]);
            }else{
                return response()->json([
                    'rc' => 1,
                    'message' => 'Chưa thanh toán',
                    'data' => null
                ]);
            }
        }else{
            return response()->json([
                'rc' => -1,
                'message' => 'Không tìm thấy học viên',
                'data' => null
            ]);
        }
    }

    public function kiemTraTrangThaiThanhToan(Request $request)
    {
        $cccd = $request->query('cccd');
        if (!$cccd) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin CCCD'
            ], 400);
        }

        // Tìm giao dịch gần nhất cho CCCD này
        $giaoDich = GiaoDich::where('transaction_content', 'like', 'DH'.$cccd.'%')
                             ->orderBy('created_at', 'desc')
                             ->first();

        if ($giaoDich) {

            $hocVien = hocPhi::where('so_cccd', $cccd)->first();
            if($hocVien){
                $hocVien->trang_thai = 'Đã thanh toán';
                $hocVien->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Đã tìm thấy giao dịch thanh toán',
                'data' => [
                    'transaction_date' => $giaoDich->transaction_date,
                    'amount' => $giaoDich->amount_in,
                    'content' => $giaoDich->transaction_content,
                    'reference_number' => $giaoDich->reference_number,
                    'created_at' => $giaoDich->created_at
                ]
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Chưa tìm thấy giao dịch thanh toán',
            'data' => null
        ]);
    }
}
