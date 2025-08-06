@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-cog me-2"></i>
            Cấu Hình Kỳ Thi
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Thông Tin Cấu Hình
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cau-hinh.store') }}" method="POST" id="cauHinhForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ngan_hang" class="form-label">
                                        <i class="fas fa-university me-1"></i>
                                        Tên ngân hàng
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('ngan_hang') is-invalid @enderror" 
                                           id="ngan_hang" 
                                           name="ngan_hang" 
                                           value="{{ old('ngan_hang', $cauHinh->ngan_hang ?? '') }}"
                                           placeholder="Nhập tên ngân hàng"
                                           required>
                                    @error('ngan_hang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Nhập tên ngân hàng để nhận thanh toán
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="so_tai_khoan" class="form-label">
                                        <i class="fas fa-credit-card me-1"></i>
                                        Số tài khoản
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('so_tai_khoan') is-invalid @enderror" 
                                           id="so_tai_khoan" 
                                           name="so_tai_khoan" 
                                           value="{{ old('so_tai_khoan', $cauHinh->so_tai_khoan ?? '') }}"
                                           placeholder="Nhập số tài khoản"
                                           required>
                                    @error('so_tai_khoan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Nhập số tài khoản ngân hàng
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="chu_tai_khoan" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Chủ tài khoản
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('chu_tai_khoan') is-invalid @enderror" 
                                           id="chu_tai_khoan" 
                                           name="chu_tai_khoan" 
                                           value="{{ old('chu_tai_khoan', $cauHinh->chu_tai_khoan ?? '') }}"
                                           placeholder="Nhập tên chủ tài khoản"
                                           required>
                                    @error('chu_tai_khoan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Nhập tên chủ tài khoản ngân hàng
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ngay_thi" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Ngày thi
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('ngay_thi') is-invalid @enderror" 
                                           id="ngay_thi" 
                                           name="ngay_thi"
                                           value="{{ old('ngay_thi', $cauHinh->ngay_thi ?? '') }}"
                                           placeholder="Nhập ngày thi (ví dụ: 15/12/2024)"
                                           required>
                                    @error('ngay_thi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Nhập ngày tổ chức kỳ thi (có thể nhập tự do)
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>  
                        <div class="text-center">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Lưu cấu hình
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông Tin Hiện Tại
                    </h5>
                </div>
                <div class="card-body">
                    @if($cauHinh)
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-university text-primary me-2"></i>
                                <div>
                                    <small class="text-muted">Tên ngân hàng</small>
                                    <div class="fw-bold">{{ $cauHinh->ngan_hang ?? 'Chưa thiết lập' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-credit-card text-success me-2"></i>
                                <div>
                                    <small class="text-muted">Số tài khoản</small>
                                    <div class="fw-bold">{{ $cauHinh->so_tai_khoan ?? 'Chưa thiết lập' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user text-info me-2"></i>
                                <div>
                                    <small class="text-muted">Chủ tài khoản</small>
                                    <div class="fw-bold">{{ $cauHinh->chu_tai_khoan ?? 'Chưa thiết lập' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar text-warning me-2"></i>
                                <div>
                                    <small class="text-muted">Ngày thi</small>
                                    <div class="fw-bold">
                                        @if($cauHinh->ngay_thi)
                                            {{ $cauHinh->ngay_thi }}
                                        @else
                                            <span class="text-muted">Chưa thiết lập</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p>Chưa có cấu hình nào được thiết lập</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Hướng Dẫn
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Tên ngân hàng phải được nhập chính xác
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Số tài khoản phải là số hợp lệ
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Ngày thi có thể nhập tự do
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Cấu hình sẽ được áp dụng cho toàn bộ hệ thống
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-eye me-2"></i>
                    Xem Trước Cấu Hình
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
.info-item {
    padding: 10px;
    border-radius: 8px;
    background-color: #f8f9fa;
    border-left: 4px solid var(--accent-color);
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, var(--accent-color), #2980b9);
    color: white;
    border-bottom: none;
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-color), #2980b9);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2980b9, var(--accent-color));
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border: none;
    color: white;
}

.btn-info:hover {
    background: linear-gradient(135deg, #138496, #17a2b8);
    color: white;
}
</style>

<script>
// Initialize form when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Form initialized');
});

function resetForm() {
    if (confirm('Bạn có chắc chắn muốn làm mới form?')) {
        document.getElementById('cauHinhForm').reset();
    }
}



function previewConfig() {
    const tenNganHang = document.getElementById('ngan_hang').value;
    const soTaiKhoan = document.getElementById('so_tai_khoan').value;
    const chuTaiKhoan = document.getElementById('chu_tai_khoan').value;
    const ngayThi = document.getElementById('ngay_thi').value;
    const ghiChu = document.getElementById('ghi_chu') ? document.getElementById('ghi_chu').value : '';
    
    if (!tenNganHang || !soTaiKhoan || !chuTaiKhoan || !ngayThi) {
        alert('Vui lòng điền đầy đủ thông tin trước khi xem trước!');
        return;
    }
    
    const previewContent = `
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Thông Tin Cấu Hình</h6>
                </div>
                
                <div class="mb-3">
                    <strong>Tên ngân hàng:</strong> ${tenNganHang}
                </div>
                
                <div class="mb-3">
                    <strong>Số tài khoản:</strong> ${soTaiKhoan}
                </div>
                
                <div class="mb-3">
                    <strong>Chủ tài khoản:</strong> ${chuTaiKhoan}
                </div>
                
                <div class="mb-3">
                    <strong>Ngày thi:</strong> ${ngayThi}
                </div>
                
                ${ghiChu ? `<div class="mb-3"><strong>Ghi chú:</strong> ${ghiChu}</div>` : ''}
                
                <div class="alert alert-warning">
                    <small><i class="fas fa-exclamation-triangle me-1"></i>Đây chỉ là xem trước. Nhấn "Lưu cấu hình" để áp dụng thay đổi.</small>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewContent;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Form validation
document.getElementById('cauHinhForm').addEventListener('submit', function(e) {
    const tenNganHang = document.getElementById('ngan_hang').value;
    const soTaiKhoan = document.getElementById('so_tai_khoan').value;
    const chuTaiKhoan = document.getElementById('chu_tai_khoan').value;
    const ngayThi = document.getElementById('ngay_thi').value;
    
    console.log('Form submission - tenNganHang:', tenNganHang);
    console.log('Form submission - soTaiKhoan:', soTaiKhoan);
    console.log('Form submission - chuTaiKhoan:', chuTaiKhoan);
    console.log('Form submission - ngayThi:', ngayThi);
    
    if (!tenNganHang || !soTaiKhoan || !chuTaiKhoan || !ngayThi) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
    }
    
    if (tenNganHang.trim().length < 2) {
        e.preventDefault();
        alert('Tên ngân hàng phải có ít nhất 2 ký tự!');
        return;
    }
    
    if (soTaiKhoan.trim().length < 8) {
        e.preventDefault();
        alert('Số tài khoản phải có ít nhất 8 ký tự!');
        return;
    }
    
    if (chuTaiKhoan.trim().length < 2) {
        e.preventDefault();
        alert('Tên chủ tài khoản phải có ít nhất 2 ký tự!');
        return;
    }
    
    console.log('Form validation passed, submitting...');
});

// Hàm kiểm tra trạng thái thanh toán mỗi giây
function startPaymentStatusCheck(cccd) {
    if (!cccd) {
        console.error('CCCD không được cung cấp');
        return;
    }

    console.log('Bắt đầu kiểm tra trạng thái thanh toán cho CCCD:', cccd);
    
    // Tạo interval để gọi API mỗi giây
    const intervalId = setInterval(async () => {
        try {
            const response = await axios.get(`/kiem-tra-trang-thai-thanh-toan?cccd=${cccd}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            console.log('Kết quả kiểm tra thanh toán:', response.data);

            if (response.data.success) {
                // Nếu tìm thấy giao dịch thanh toán
                clearInterval(intervalId);
                
                // Hiển thị thông báo thành công
                showPaymentSuccessNotification(response.data.data);
                
                // Có thể redirect hoặc cập nhật UI
                setTimeout(() => {
                    window.location.href = '/thanh-toan-thanh-cong';
                }, 2000);
                
            } else {
                // Chưa tìm thấy giao dịch, tiếp tục kiểm tra
                console.log('Chưa tìm thấy giao dịch thanh toán, tiếp tục kiểm tra...');
            }

        } catch (error) {
            console.error('Lỗi khi kiểm tra trạng thái thanh toán:', error);
            
            // Nếu lỗi network, dừng kiểm tra
            if (error.response && error.response.status >= 500) {
                clearInterval(intervalId);
                showPaymentErrorNotification('Lỗi kết nối server');
            }
        }
    }, 1000); // Gọi mỗi 1 giây

    // Lưu interval ID để có thể dừng sau này
    window.paymentCheckInterval = intervalId;
    
    // Trả về function để dừng kiểm tra
    return () => {
        clearInterval(intervalId);
        window.paymentCheckInterval = null;
        console.log('Đã dừng kiểm tra trạng thái thanh toán');
    };
}

// Hàm dừng kiểm tra trạng thái thanh toán
function stopPaymentStatusCheck() {
    if (window.paymentCheckInterval) {
        clearInterval(window.paymentCheckInterval);
        window.paymentCheckInterval = null;
        console.log('Đã dừng kiểm tra trạng thái thanh toán');
    }
}

// Hàm hiển thị thông báo thành công
function showPaymentSuccessNotification(data) {
    // Tạo thông báo Bootstrap
    const notification = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Thanh toán thành công!</strong><br>
            Số tiền: ${data.amount.toLocaleString('vi-VN')} VNĐ<br>
            Nội dung: ${data.content}<br>
            Thời gian: ${new Date(data.created_at).toLocaleString('vi-VN')}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Thêm thông báo vào đầu trang
    const container = document.querySelector('.content-wrapper');
    if (container) {
        container.insertAdjacentHTML('afterbegin', notification);
    }
}

// Hàm hiển thị thông báo lỗi
function showPaymentErrorNotification(message) {
    const notification = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Lỗi!</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    const container = document.querySelector('.content-wrapper');
    if (container) {
        container.insertAdjacentHTML('afterbegin', notification);
    }
}

// Hàm khởi tạo kiểm tra thanh toán (có thể gọi từ nút hoặc form)
function initPaymentStatusCheck() {
    // Lấy CCCD từ URL parameter hoặc input field
    const urlParams = new URLSearchParams(window.location.search);
    const cccd = urlParams.get('cccd') || document.getElementById('cccd')?.value;
    
    if (cccd) {
        return startPaymentStatusCheck(cccd);
    } else {
        console.error('Không tìm thấy CCCD để kiểm tra thanh toán');
        return null;
    }
}

// Tự động dừng kiểm tra khi rời khỏi trang
window.addEventListener('beforeunload', function() {
    stopPaymentStatusCheck();
});

// Export các function để có thể sử dụng từ bên ngoài
window.paymentStatusCheck = {
    start: startPaymentStatusCheck,
    stop: stopPaymentStatusCheck,
    init: initPaymentStatusCheck
};
</script>
@endsection