<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thanh Toán</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .card-header {
            background: linear-gradient(135deg, var(--accent-color), #2980b9);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            border: none;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            transform: translateY(-2px);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        }

        .btn {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color), #2980b9);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, #1f5f8b);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #229954);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #229954, #1e8449);
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        .qr-container {
            background: linear-gradient(45deg, #f8f9fa 25%, transparent 25%), 
                        linear-gradient(-45deg, #f8f9fa 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #f8f9fa 75%), 
                        linear-gradient(-45deg, transparent 75%, #f8f9fa 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            border-radius: 15px;
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 250px;
        }

        .qr-placeholder {
            text-align: center;
            color: #6c757d;
        }

        .qr-placeholder i {
            font-size: 4rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .price-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--accent-color);
        }

        .product-item {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--success-color), #229954);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 20px;
        }

        .info-box {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: var(--dark-color);
        }

        .info-value {
            font-weight: 700;
            color: var(--accent-color);
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .success-animation {
            animation: successPulse 0.6s ease-in-out;
        }

        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .page-title {
                font-size: 2rem;
            }

            .card-body {
                padding: 20px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }

            .qr-container {
                min-height: 200px;
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }

            .card-body {
                padding: 15px;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-credit-card me-3"></i>
                Thanh Toán
            </h1>
            <p class="page-subtitle">Hoàn tất đơn hàng của bạn một cách an toàn và nhanh chóng</p>
        </div>

        <div class="row">
            <!-- Thông tin đơn hàng - Bên trái -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Thông Tin Đơn Hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Mã đơn hàng:</label>
                                <p class="form-control-plaintext fw-bold">#DH{{ $cccd }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày tạo:</label>
                                <p class="form-control-plaintext fw-bold">{{ date('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="price-item">
                            <span class="fw-bold">Tạm tính:</span>
                            <span>{{ number_format($soTien, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="price-item">
                            <span class="fw-bold">Phí dịch vụ:</span>
                            <span>0 VNĐ</span>
                        </div>
                        <div class="price-item">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-5 text-primary">{{ number_format($soTien * 1, 0, ',', '.') }} VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code và thông tin người nhận - Bên phải -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-qrcode me-2"></i>
                            Thanh Toán QR Code
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- QR Code -->
                        <div class="text-center mb-4">
                            <div class="qr-container">
                                <div class="qr-placeholder">
                                    <img src="{{ $qrCode }}" alt="QR Code">
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin người nhận -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-user me-2"></i>
                                Thông Tin Người Nhận
                            </h6>
                            <div class="info-box">
                                <div class="info-item">
                                    <span class="info-label">Tên:</span>
                                    <span class="info-value">{{ $cauHinh->chu_tai_khoan }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Số tài khoản:</span>
                                    <span class="info-value">{{ $cauHinh->so_tai_khoan }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Ngân hàng:</span>
                                    <span class="info-value">{{ $cauHinh->ngan_hang }}</span>
                                </div>
                            </div>
                        </div>



                        <!-- Nút thanh toán -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary" id="btnHuy">
                                <i class="fas fa-times me-2"></i>
                                Hủy Đơn Hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận thanh toán -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>
                        Xác Nhận Thanh Toán
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn thanh toán đơn hàng này?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Vui lòng kiểm tra kỹ thông tin trước khi xác nhận!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" id="btnXacNhan">
                        <i class="fas fa-check me-2"></i>
                        Xác Nhận
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Biến để lưu interval ID
            let paymentCheckInterval;
            
            // Hàm kiểm tra trạng thái thanh toán
            function checkPaymentStatus() {
                axios.get('/kiem-tra-trang-thai-thanh-toan', {
                    params: {
                        cccd: '{{ $cccd }}'
                    }
                })
                .then(function (response) {
                    console.log('Trạng thái thanh toán:', response.data);
                    
                    // Kiểm tra nếu thanh toán thành công
                    if (response.data.success === true) {
                        // Dừng interval
                        if (paymentCheckInterval) {
                            clearInterval(paymentCheckInterval);
                        }
                        
                        // Hiển thị thông báo thành công
                        showSuccessMessage('Thanh toán thành công! Giao dịch đã được xác nhận.');
                        // Chuyển hướng sau 3 giây
                        setTimeout(() => {
                            window.location.href = '/hoan-thanh';
                        }, 3000);
                    }
                })
                .catch(function (error) {
                    console.error('Lỗi khi kiểm tra trạng thái thanh toán:', error);
                });
            }
            
            // Hàm hiển thị thông báo thành công
            function showSuccessMessage(message) {
                // Tạo alert thành công
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show success-animation';
                successAlert.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>${message}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                // Thêm alert vào đầu container
                const container = document.querySelector('.container');
                container.insertBefore(successAlert, container.firstChild);
                
                // Scroll to top to show success message
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            
            // Bắt đầu kiểm tra trạng thái thanh toán mỗi giây
            paymentCheckInterval = setInterval(checkPaymentStatus, 1000);

            // Xử lý nút hủy
            const btnHuy = document.getElementById('btnHuy');
            if (btnHuy) {
                btnHuy.addEventListener('click', function() {
                    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                        // Dừng interval trước khi chuyển trang
                        if (paymentCheckInterval) {
                            clearInterval(paymentCheckInterval);
                        }
                        window.history.back();
                    }
                });
            }

            // Add smooth scrolling for better UX
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
            
            // Dừng interval khi trang bị unload
            window.addEventListener('beforeunload', function() {
                if (paymentCheckInterval) {
                    clearInterval(paymentCheckInterval);
                }
            });
        });
    </script>
</body>
</html>
