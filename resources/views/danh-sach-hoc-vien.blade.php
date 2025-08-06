<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Danh sách học viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <style>
        .container {
            margin-top: 30px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .empty-state {
            text-align: center;
            padding: 50px 0;
            color: #6c757d;
        }
        .search-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .export-btn-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h2 class="mb-4">Danh sách học viên đăng ký thi</h2>
        
        <!-- Form tìm kiếm -->
        <div class="search-form">
            <form action="{{ route('danh-sach-hoc-vien') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="keyword" class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" id="keyword" name="keyword" 
                           placeholder="Nhập tên, CCCD..." value="{{ request('keyword') }}">
                </div>
                <div class="col-md-3">
                    <label for="ngay_hoc" class="form-label">Ngày học</label>
                    <input type="date" class="form-control" id="ngay_hoc" name="ngay_hoc" value="{{ request('ngay_hoc') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>

        <div class="search-form">
        @if(isset($satHaches) && count($satHaches) > 0)
            <div class="export-btn-container">
                <button class="btn btn-success" onclick="exportToExcel()">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="hocVienTable">
                    <thead class="table-dark">
                        <tr>
                            <th>STT</th>
                            <th>Họ và tên</th>
                            <th>CCCD</th>
                            <th>Khoá học</th>
                            <th>Ngày sinh</th>
                            <th>Địa chỉ</th>
                            <th>Ngày thi</th>
                            <th>Nội dung thi</th>
                            <th>Ngày sát hạch</th>
                            <th>Giáo viên</th>
                            <th>Lý thuyết</th>
                            <th>Thực hành</th>
                            <th>Mô phỏng</th>
                            <th>Đường trường</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($satHaches as $index => $satHach)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $satHach->hocVien->ho . ' ' . $satHach->hocVien->ten }}</td>
                                <td>{{ $satHach->cccd }}</td>
                                <td>{{ $satHach->hocVien->khoa_hoc }}</td>
                                <td>{{ \Carbon\Carbon::parse($satHach->hocVien->ngay_sinh)->format('d/m/Y') }}</td>
                                <td>{{ $satHach->hocVien->dia_chi }}</td>
                                <td>{{ \Carbon\Carbon::parse($satHach->ngay_thi)->format('d/m/Y') }}</td>
                                <td>{{ $satHach->hocVien->noi_dung_thi }}</td>
                                <td>{{ \Carbon\Carbon::parse($satHach->ngay_sat_hach)->format('d/m/Y') }}</td>
                                <td>{{ $satHach->hocVien->dau_moi }}</td>
                                <td>{{ $satHach->hocVien->ly_thuyet }}</td>
                                <td>{{ $satHach->hocVien->thuc_hanh }}</td>
                                <td>{{ $satHach->hocVien->mo_phong }}</td>
                                <td>{{ $satHach->hocVien->duong_truong }}</td>
                                <td>
                                    @if($satHach->hocVien->ly_thuyet == 'Đạt' && $satHach->hocVien->thuc_hanh == 'Đạt' && $satHach->hocVien->mo_phong == 'Đạt' && $satHach->hocVien->duong_truong == 'Đạt')
                                        <span class="badge bg-success">Đạt</span>
                                    @else
                                        <span class="badge bg-danger">Chưa đạt</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $satHach->id }}">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal chỉnh sửa -->
            @foreach($satHaches as $satHach)
            <div class="modal fade" id="editModal{{ $satHach->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $satHach->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $satHach->id }}">Chỉnh sửa thông tin học viên</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('cap-nhat-hoc-vien', ['id' => $satHach->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="ten_nguoi_dung" class="form-label">Họ và tên</label>
                                        <input type="text" disabled class="form-control" id="ten_nguoi_dung" name="ten_nguoi_dung" value="{{ $satHach->hocVien->ho . ' ' . $satHach->hocVien->ten }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cccd" class="form-label">CCCD</label>
                                        <input type="text" disabled class="form-control" id="cccd" name="cccd" value="{{ $satHach->cccd }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh" value="{{ $satHach->hocVien->ngay_sinh }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="khoa_hoc" class="form-label">Khóa học</label>
                                        <input type="text" disabled class="form-control" id="khoa_hoc" name="khoa_hoc" value="{{ $satHach->hocVien->khoa_hoc }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="giao_vien" class="form-label">Giáo viên</label>
                                        <input type="text" class="form-control" id="giao_vien" name="giao_vien" value="{{ $satHach->hocVien->dau_moi }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ngay_hoc" class="form-label">Ngày học</label>
                                        <input type="date" class="form-control" id="ngay_hoc" name="ngay_hoc" value="{{ $satHach->ngay_thi }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="export-btn-container">
                <button class="btn btn-success" disabled>
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </button>
            </div>
            <div class="empty-state">
                <h4>Chưa có học viên nào đăng ký</h4>
                <p>Vui lòng quay lại sau khi có học viên đăng ký.</p>
            </div>
        @endif
        </div>
    </div>

    <div class="container text-center mb-4">
        <a href="/" class="btn btn-primary">Về trang chủ</a>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="statusToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Thông báo</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportToExcel() {
            // Lấy bảng cần xuất
            const table = document.getElementById('hocVienTable');
            
            // Tạo bảng tạm thời để loại bỏ cột cuối
            const tempTable = table.cloneNode(true);
            const rows = tempTable.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                rows[i].deleteCell(-1); // Xóa cột cuối cùng
            }
            
            // Tạo workbook mới
            const wb = XLSX.utils.book_new();
            
            // Chuyển đổi bảng thành worksheet
            const ws = XLSX.utils.table_to_sheet(tempTable, {
                raw: true, // Giữ nguyên định dạng dữ liệu
                dateNF: 'yyyy-mm-dd', // Định dạng ngày tháng
                cellDates: true // Xử lý đúng định dạng ngày tháng
            });
            
            // Đặt độ rộng cột
            const wscols = [
                {wch: 5},  // STT
                {wch: 20}, // Họ và tên
                {wch: 15}, // CCCD
                {wch: 20}, // Khoá học
                {wch: 12}, // Ngày sinh
                {wch: 20}, // Giáo viên
                {wch: 12}, // Ngày học
                {wch: 10}, // Giờ học
                {wch: 12}  // Trạng thái
            ];
            ws['!cols'] = wscols;
            
            // Thêm worksheet vào workbook
            XLSX.utils.book_append_sheet(wb, ws, "Danh sách học viên");
            
            // Xuất file Excel
            XLSX.writeFile(wb, "danh-sach-hoc-vien.xlsx");
        }

        // Function to show toast
        function showToast(title, message, isSuccess = true) {
            const toast = document.getElementById('statusToast');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            
            // Set toast content
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            
            // Set toast style based on success/error
            toast.classList.remove('bg-success', 'bg-danger', 'text-white');
            if (isSuccess) {
                toast.classList.add('bg-success', 'text-white');
            } else {
                toast.classList.add('bg-danger', 'text-white');
            }
            
            // Show toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        // Add status update functionality
        document.addEventListener('DOMContentLoaded', function() {
            const statusSwitches = document.querySelectorAll('.status-switch');
            
            statusSwitches.forEach(statusSwitch => {
                // Update label when switch changes
                statusSwitch.addEventListener('change', function() {
                    const label = this.nextElementSibling;
                    label.textContent = this.checked ? 'Đã học' : 'Chưa học';
                    
                    const hocVienId = this.dataset.id;
                    const newStatus = this.checked ? 1 : 0;
                    
                    // Show loading state
                    this.disabled = true;
                    
                    // Send AJAX request
                    fetch(`/cap-nhat-trang-thai/${hocVienId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            trang_thai: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.rc == 0) {
                            showToast('Thành công', 'Cập nhật trạng thái thành công!', true);
                        } else {
                            // Revert switch if update failed
                            this.checked = !this.checked;
                            label.textContent = this.checked ? 'Đã học' : 'Chưa học';
                            showToast('Lỗi', 'Có lỗi xảy ra khi cập nhật trạng thái!', false);
                        }
                    })
                    .catch(error => {
                        // Revert switch if request failed
                        this.checked = !this.checked;
                        label.textContent = this.checked ? 'Đã học' : 'Chưa học';
                        showToast('Lỗi', 'Có lỗi xảy ra khi cập nhật trạng thái!', false);
                    })
                    .finally(() => {
                        // Re-enable switch
                        this.disabled = false;
                    });
                });
            });
        });
    </script>
</body>
</html>
