@extends('layouts.app')
@section('content')
<div class="container"
    style="width: 100% !important;margin:0 !important;padding:0 !important; max-width: 100% !important;">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Danh sách học viên</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" type="button" id="importBtn">
                        <i class="fas fa-file-excel me-2"></i>Import Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Bộ lọc -->

            <!-- Filter Form -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc tìm kiếm</h6>
                        </div>
                        <div class="card-body">

                            <form class="row g-2 align-items-end mb-2" method="GET" action="">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm theo CCCD, họ hoặc tên" value="{{ request('tu_khoa') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary w-100" type="submit" title="Tìm kiếm">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success w-100" id="exportExcelBtn" title="Xuất Excel" onclick="exportExcel()">
                                        <i class="fas fa-file-excel"></i> Xuất Excel
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ url()->current() }}" class="btn btn-danger w-100" title="Xóa bộ lọc">
                                        <i class="fas fa-times"></i> Xóa bộ lọc
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <style>
                    #hocVienTable th, #hocVienTable td {
                        white-space: nowrap;
                    }
                </style>
                <table class="table table-striped table-hover table-bordered" id="hocVienTable">
                    <thead class="table-dark">
                        <tr>
                            <th>STT</th>
                            <th>Họ</th>
                            <th style="width: 100px;">Tên</th>
                            <th>Ngày sinh</th>
                            <th>Số báo danh</th>
                            <th>Địa chỉ</th>
                            <th>Khóa học</th>
                            <th>Nội dung thi</th>
                            <th>Ngày sát hạch</th>
                            <th>Đầu mối</th>
                            <th>Ghi chú</th>
                            <th>Lý thuyết</th>
                            <th>Mô phỏng</th>
                            <th>Thực hành</th>
                            <th>Đường trường</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hocViens ?? [] as $index => $hocVien)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $hocVien->ho }}</td>
                            <td>{{ $hocVien->ten }}</td>
                            <td>{{ $hocVien->ngay_sinh }}</td>
                            <td>{{ $hocVien->cccd ?? 'Không có'}}</td>
                            <td>{{ $hocVien->dia_chi }}</td>
                            <td>{{ $hocVien->khoa_hoc }}</td>
                            <td>{{ $hocVien->noi_dung_thi }}</td>
                            <td>{{ $hocVien->ngay_sat_hach }}</td>
                            <td>{{ $hocVien->dau_moi }}</td>
                            <td>
                                <span class="text-muted">
                                    {{ $hocVien->ghi_chu ?? ' ' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $hocVien->ly_thuyet >= 50 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $hocVien->ly_thuyet }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $hocVien->mo_phong >= 50 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $hocVien->mo_phong }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $hocVien->thuc_hanh >= 50 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $hocVien->thuc_hanh }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $hocVien->duong_truong >= 50 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $hocVien->duong_truong }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="editHocVien({{ $hocVien->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteHocVien({{ $hocVien->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="16" class="text-center text-muted">Chưa có dữ liệu học viên</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa học viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_ho" class="form-label">Họ</label>
                                <input type="text" class="form-control" id="edit_ho" name="ho" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_ten" class="form-label">Tên</label>
                                <input type="text" class="form-control" id="edit_ten" name="ten" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_ngay_sinh" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="edit_ngay_sinh" name="ngay_sinh" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_cccd" class="form-label">CCCD</label>
                                <input type="text" class="form-control" id="edit_cccd" name="cccd" readonly
                                    style="background-color: #f8f9fa;">
                                <small class="form-text text-muted">CCCD không thể chỉnh sửa</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_dia_chi" class="form-label">Địa chỉ</label>
                        <textarea class="form-control" id="edit_dia_chi" name="dia_chi" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_khoa_hoc" class="form-label">Khóa học</label>
                                <input type="text" class="form-control" id="edit_khoa_hoc" name="khoa_hoc" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_noi_dung_thi" class="form-label">Nội dung thi</label>
                                <input type="text" class="form-control" id="edit_noi_dung_thi" name="noi_dung_thi"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_ngay_sat_hach" class="form-label">Ngày sát hạch</label>
                                <input type="date" class="form-control" id="edit_ngay_sat_hach" name="ngay_sat_hach"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_dau_moi" class="form-label">Đầu mối</label>
                                <input type="text" class="form-control" id="edit_dau_moi" name="dau_moi" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_ghi_chu" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="edit_ghi_chu" name="ghi_chu" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_ly_thuyet" class="form-label">Lý thuyết</label>
                                <input type="text" class="form-control" id="edit_ly_thuyet" name="ly_thuyet" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_mo_phong" class="form-label">Mô phỏng</label>
                                <input type="text" class="form-control" id="edit_mo_phong" name="mo_phong" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_thuc_hanh" class="form-label">Thực hành</label>
                                <input type="text" class="form-control" id="edit_thuc_hanh" name="thuc_hanh" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_duong_truong" class="form-label">Đường trường</label>
                                <input type="text" class="form-control" id="edit_duong_truong" name="duong_truong"
                                    required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="updateHocVien()" id="updateBtn">
                    <i class="fas fa-save me-2"></i>Cập nhật
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-excel me-2"></i>Import dữ liệu từ Excel/CSV
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Tải lên file Excel/CSV</h6>
                            </div>
                            <div class="card-body">
                                <form id="importForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="excelFile" class="form-label">Chọn file Excel hoặc CSV (.xlsx, .xls,
                                            .csv)</label>
                                        <input type="file" class="form-control" id="excelFile" name="excel_file"
                                            accept=".xlsx,.xls,.csv" required>
                                        <div class="form-text">Hỗ trợ định dạng .xlsx, .xls và .csv. Khuyến nghị sử dụng file mẫu XLSX</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Cấu trúc file Excel</label>
                                        <div class="alert alert-info">
                                            <p class="mb-1"><strong>Dữ liệu sẽ được đọc từ hàng thứ 2 (bỏ qua hàng tiêu
                                                    đề) với các cột:</strong></p>
                                            <ul class="mb-0">
                                                <li>Họ, Tên, Ngày sinh, CCCD, Địa chỉ</li>
                                                <li>Khóa học, Nội dung thi, Ngày sát hạch (có thể nhập tự do), Đầu mối</li>
                                                <li>Ghi chú, Lý thuyết, Mô phỏng, Thực hành, Đường trường</li>
                                            </ul>
                                            <p class="mb-0 mt-2"><small class="text-muted">Lưu ý: Ngày sát hạch có thể nhập dưới dạng text tự do (ví dụ: "Tháng 1/2023", "Q1/2023", "T1/2023", v.v.)</small></p>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="previewBtn">
                                            <i class="fas fa-upload me-2"></i>Import dữ liệu
                                        </button>
                                        <a href="{{ route('hoc-vien.download-template') }}" class="btn btn-secondary">
                                            <i class="fas fa-download me-2"></i>Tải mẫu XLSX
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Kết quả xử lý -->
                <div id="importResult" class="mt-3" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Kết quả xử lý</h6>
                        </div>
                        <div class="card-body">
                            <div id="resultContent"></div>
                        </div>
                    </div>
                </div>

                <!-- Bảng xem trước dữ liệu -->
                <div id="previewSection" class="mt-3" style="display: none;">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Xem trước dữ liệu</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success btn-sm" id="confirmImportBtn">
                                    <i class="fas fa-check me-2"></i>Xác nhận Import
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="hidePreview()">
                                    <i class="fas fa-times me-2"></i>Đóng
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="previewTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>STT</th>
                                            <th>Họ</th>
                                            <th>Tên</th>
                                            <th>Ngày sinh</th>
                                            <th>CCCD</th>
                                            <th>Địa chỉ</th>
                                            <th>Khóa học</th>
                                            <th>Nội dung thi</th>
                                            <th>Ngày sát hạch</th>
                                            <th>Đầu mối</th>
                                            <th>Ghi chú</th>
                                            <th>Lý thuyết</th>
                                            <th>Mô phỏng</th>
                                            <th>Thực hành</th>
                                            <th>Đường trường</th>
                                        </tr>
                                    </thead>
                                    <tbody id="previewTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thông báo lỗi -->
<div class="modal fade" id="errorModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Thông báo lỗi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="errorContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    // Function để parse CSV
    function CSVToArray(strData, strDelimiter) {
        strDelimiter = (strDelimiter || ",");
        const objPattern = new RegExp(
            ("(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +
                "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +
                "([^\"\\" + strDelimiter + "\\r\\n]*))"), "gi"
        );
        const arrData = [
            []
        ];
        let arrMatches = null;
        while (arrMatches = objPattern.exec(strData)) {
            const strMatchedDelimiter = arrMatches[1];
            if (strMatchedDelimiter.length && strMatchedDelimiter !== strDelimiter) {
                arrData.push([]);
            }
            let strMatchedValue;
            if (arrMatches[2]) {
                strMatchedValue = arrMatches[2].replace(new RegExp("\"\"", "g"), "\"");
            } else {
                strMatchedValue = arrMatches[3];
            }
            arrData[arrData.length - 1].push(strMatchedValue);
        }
        return arrData;
    }

    // Mở modal import Excel
    document.getElementById('importBtn').addEventListener('click', function () {
        new bootstrap.Modal(document.getElementById('importModal')).show();
    });

    // Biến lưu trữ dữ liệu xem trước
    let previewData = [];

    // Xử lý form import Excel
    document.getElementById('importForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const fileInput = document.getElementById('excelFile');
        const file = fileInput.files[0];

        if (!file) {
            showErrorModal('Lỗi chọn file!', 'Vui lòng chọn file Excel hoặc CSV để import.');
            return;
        }

        // Tạo FormData để gửi file
        const formData = new FormData();
        formData.append('excel_file', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Hiển thị loading
        const previewBtn = document.getElementById('previewBtn');
        const originalText = previewBtn.innerHTML;
        previewBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
        previewBtn.disabled = true;

        // Gửi request import
        fetch('{{ route("hoc-vien.import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.rc === 0) {
                showSuccessModal('Import thành công!', data.message);
                // Reload trang để hiển thị dữ liệu mới
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showErrorModal('Import thất bại!', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('Lỗi hệ thống!', 'Có lỗi xảy ra khi import dữ liệu.');
        })
        .finally(() => {
            // Khôi phục nút
            previewBtn.innerHTML = originalText;
            previewBtn.disabled = false;
        });
    });

    // Xử lý form xem trước Excel (giữ lại cho tương lai nếu cần)
    document.getElementById('previewForm')?.addEventListener('submit', function (e) {
        e.preventDefault();

        const fileInput = document.getElementById('excelFile');
        const file = fileInput.files[0];

        if (!file) {
            showErrorModal('Lỗi chọn file!', 'Vui lòng chọn file Excel hoặc CSV để import.');
            return;
        }

        const previewBtn = document.getElementById('previewBtn');
        const previewSection = document.getElementById('previewSection');
        const resultDiv = document.getElementById('importResult');
        const resultContent = document.getElementById('resultContent');

        // Disable button và hiển thị loading
        previewBtn.disabled = true;
        previewBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';

        // Hiển thị thông báo loading
        resultDiv.style.display = 'block';
        resultContent.innerHTML = `
        <div class="alert alert-info">
            <h6><i class="fas fa-spinner fa-spin me-2"></i>Đang đọc file...</h6>
            <p class="mb-0">Vui lòng chờ trong khi hệ thống đang xử lý file Excel/CSV.</p>
            <div class="progress mt-2">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
            </div>
        </div>
    `;

        // Đọc file Excel bằng SheetJS
        const reader = new FileReader();
        reader.onload = function (e) {
            try {
                const data = new Uint8Array(e.target.result);
                let jsonData;

                // Kiểm tra loại file
                if (file.name.toLowerCase().endsWith('.csv')) {
                    // Xử lý file CSV
                    const csvText = new TextDecoder().decode(data);
                    jsonData = CSVToArray(csvText);
                } else {
                    // Xử lý file Excel
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const sheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[sheetName];
                    jsonData = XLSX.utils.sheet_to_json(worksheet, {
                        header: 1
                    });
                }

                // Bỏ qua hàng tiêu đề (hàng đầu tiên)
                const headers = jsonData.shift();

                // Lọc bỏ các hàng trống và format dữ liệu
                const dataRows = [];
                let actualDataIndex = 0; // Đếm số thứ tự thực tế của dữ liệu

                jsonData.forEach((row, index) => {
                    // Kiểm tra xem hàng có phải là hàng tiêu đề không (chứa các từ khóa tiêu đề)
                    const isHeaderRow = row && row.length > 0 && (
                        String(row[0]).toLowerCase().includes('họ') ||
                        String(row[0]).toLowerCase().includes('ho') ||
                        String(row[1]).toLowerCase().includes('tên') ||
                        String(row[1]).toLowerCase().includes('ten') ||
                        String(row[2]).toLowerCase().includes('ngày sinh') ||
                        String(row[2]).toLowerCase().includes('ngay sinh') ||
                        String(row[3]).toLowerCase().includes('cccd') ||
                        String(row[3]).toLowerCase().includes('cmnd')
                    );

                    // Chỉ xử lý các hàng không phải tiêu đề và có dữ liệu
                    if (row && row.some(cell => cell !== null && cell !== '') && !isHeaderRow) {
                        actualDataIndex++; // Tăng số thứ tự thực tế
                        dataRows.push({
                            'row_number': index +
                            2, // Số thứ tự hàng trong Excel (bắt đầu từ hàng 2)
                            'display_index': actualDataIndex, // Số thứ tự hiển thị trong bảng xem trước
                            'ho': String(row[0] || ''),
                            'ten': String(row[1] || ''),
                            'ngay_sinh': String(row[2] || ''),
                            'cccd': String(row[3] || ''),
                            'dia_chi': String(row[4] || ''),
                            'khoa_hoc': String(row[5] || ''),
                            'noi_dung_thi': String(row[6] || ''),
                            'ngay_sat_hach': String(row[7] || ''),
                            'dau_moi': String(row[8] || ''),
                            'ghi_chu': String(row[9] || ''),
                            'ly_thuyet': String(row[10] || '0'),
                            'mo_phong': String(row[11] || '0'),
                            'thuc_hanh': String(row[12] || '0'),
                            'duong_truong': String(row[13] || '0'),
                            'hoc_phi': String(row[14] || '0'),
                            'da_thanh_toan': String(row[15] || 'Không')
                        });
                    }
                });

                // Lưu dữ liệu để import sau
                previewData = dataRows;

                // Hiển thị bảng xem trước
                displayPreviewTable(dataRows);
                previewSection.style.display = 'block';

                // Hiển thị thông báo thành công
                resultDiv.style.display = 'block';
                resultContent.innerHTML = `
                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle me-2"></i>Đọc file thành công!</h6>
                    <p class="mb-0">Tìm thấy ${dataRows.length} dòng dữ liệu. Vui lòng kiểm tra và xác nhận import.</p>
                </div>
            `;

                // Nếu không có dữ liệu hợp lệ, hiển thị thông báo lỗi
                if (dataRows.length === 0) {
                    showErrorModal('Không có dữ liệu hợp lệ!',
                        'File không chứa dữ liệu hợp lệ hoặc tất cả các hàng đều bị bỏ qua. Vui lòng kiểm tra lại file.'
                        );
                }

            } catch (error) {
                showErrorModal('Lỗi đọc file!',
                    `Không thể đọc file. Vui lòng kiểm tra định dạng file.<br><br><strong>Chi tiết lỗi:</strong><br>${error.message}`
                    );
            }

            // Reset button
            previewBtn.disabled = false;
            previewBtn.innerHTML = '<i class="fas fa-eye me-2"></i>Xem trước dữ liệu';
        };

        reader.onerror = function () {
            showErrorModal('Lỗi đọc file!', `Không thể đọc file. Vui lòng thử lại.<br><br><strong>Nguyên nhân có thể:</strong><br>
        <ul>
            <li>File bị hỏng hoặc không đúng định dạng</li>
            <li>File quá lớn</li>
            <li>Trình duyệt không hỗ trợ định dạng file này</li>
        </ul>`);

            // Reset button
            previewBtn.disabled = false;
            previewBtn.innerHTML = '<i class="fas fa-eye me-2"></i>Xem trước dữ liệu';
        };

        reader.readAsArrayBuffer(file);
    });

    // Hiển thị bảng xem trước
    function displayPreviewTable(data) {
        const tbody = document.getElementById('previewTableBody');
        tbody.innerHTML = '';

        data.forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td>${row.display_index || (index + 1)}</td>
            <td>${row.ho || ''}</td>
            <td>${row.ten || ''}</td>
            <td>${row.ngay_sinh || ''}</td>
            <td>${row.cccd || ''}</td>
            <td>${row.dia_chi || ''}</td>
            <td>${row.khoa_hoc || ''}</td>
            <td>${row.noi_dung_thi || ''}</td>
            <td>${row.ngay_sat_hach || ''}</td>
            <td>${row.dau_moi || ''}</td>
            <td>
                <span class="text-muted">
                    ${row.ghi_chu || ' '}
                </span>
            </td>
            <td>
                <span class="badge ${parseInt(row.ly_thuyet) >= 50 ? 'bg-success' : 'bg-danger'}">
                    ${row.ly_thuyet || 0}
                </span>
            </td>
            <td>
                <span class="badge ${parseInt(row.mo_phong) >= 50 ? 'bg-success' : 'bg-danger'}">
                    ${row.mo_phong || 0}
                </span>
            </td>
            <td>
                <span class="badge ${parseInt(row.thuc_hanh) >= 50 ? 'bg-success' : 'bg-danger'}">
                    ${row.thuc_hanh || 0}
                </span>
            </td>
            <td>
                <span class="badge ${parseInt(row.duong_truong) >= 50 ? 'bg-success' : 'bg-danger'}">
                    ${row.duong_truong || 0}
                </span>
            </td>
            <td>
                <span class="text-success fw-bold">
                    ${parseInt(row.hoc_phi).toLocaleString('vi-VN')} VNĐ
                </span>
            </td>
            <td>
                <span class="badge ${row.da_thanh_toan === 'Có' ? 'bg-success' : 'bg-warning'}">
                    ${row.da_thanh_toan || 'Không'}
                </span>
            </td>
        `;
            tbody.appendChild(tr);
        });
    }

    // Ẩn bảng xem trước
    function hidePreview() {
        document.getElementById('previewSection').style.display = 'none';
        document.getElementById('importResult').style.display = 'none';
        previewData = [];
    }

    // Xử lý nút xác nhận import
    document.getElementById('confirmImportBtn').addEventListener('click', function () {
        if (previewData.length === 0) {
            showErrorModal('Không có dữ liệu!',
                'Không có dữ liệu để import. Vui lòng chọn file có dữ liệu hợp lệ.');
            return;
        }

        if (!confirm(`Bạn có chắc chắn muốn import ${previewData.length} dòng dữ liệu vào hệ thống?`)) {
            return;
        }

        const confirmBtn = this;
        const resultDiv = document.getElementById('importResult');
        const resultContent = document.getElementById('resultContent');

        // Disable button và hiển thị loading
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang import...';

        // Hiển thị thông báo loading
        resultDiv.style.display = 'block';
        resultContent.innerHTML = `
        <div class="alert alert-info">
            <h6><i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý import...</h6>
            <p class="mb-0">Vui lòng chờ trong khi hệ thống đang import ${previewData.length} dòng dữ liệu.</p>
            <div class="progress mt-2">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
            </div>
        </div>
    `;

        // Gửi dữ liệu JSON để import
        fetch('/hoc-vien/import-json', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    data: previewData
                })
            })
            .then(response => response.json())
            .then(data => {
                // Reset button
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Xác nhận Import';

                // Hiển thị kết quả
                resultDiv.style.display = 'block';

                if (data.success) {
                    let resultHtml = `
                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle me-2"></i>Import thành công!</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success mb-0">${data.total}</h4>
                                <small>Tổng số dòng</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success mb-0">${data.success_count}</h4>
                                <small>Thành công</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger mb-0">${data.error_count}</h4>
                                <small>Lỗi</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info mb-0">${data.duplicate_count || 0}</h4>
                                <small>Trùng lặp</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                    // Hiển thị chi tiết lỗi nếu có
                    if (data.errors && data.errors.length > 0) {
                        resultHtml += `
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Chi tiết lỗi:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-warning">
                                    <tr>
                                        <th>STT</th>
                                        <th>Lỗi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.errors.map((error, index) => `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${error.message || error}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
                    }

                    // Hiển thị thông báo thành công chi tiết
                    if (data.success_count > 0) {
                        resultHtml += `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Thông báo:</h6>
                        <ul class="mb-0">
                            <li>Đã import thành công ${data.success_count} học viên vào hệ thống</li>
                            ${data.error_count > 0 ? `<li>Có ${data.error_count} dòng bị lỗi và không được import</li>` : ''}
                            ${data.duplicate_count > 0 ? `<li>Có ${data.duplicate_count} dòng bị trùng lặp CCCD</li>` : ''}
                            <li>Trang sẽ được tải lại sau 3 giây để hiển thị dữ liệu mới</li>
                        </ul>
                    </div>
                `;
                    }

                    resultContent.innerHTML = resultHtml;

                    // Nếu import thành công, reload trang sau 3 giây
                    if (data.success_count > 0) {
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    }
                } else {
                    let errorMessage = data.message || 'Đã xảy ra lỗi khi import dữ liệu';
                    if (data.errors && data.errors.length > 0) {
                        errorMessage += '<br><br><strong>Chi tiết lỗi:</strong><br><ul>';
                        data.errors.forEach(error => {
                            errorMessage += `<li>${error}</li>`;
                        });
                        errorMessage += '</ul>';
                    }
                    showErrorModal('Import thất bại!', errorMessage);
                }
            })
            .catch(error => {
                // Reset button
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Xác nhận Import';

                showErrorModal('Lỗi hệ thống!', 'Đã xảy ra lỗi khi import dữ liệu. Vui lòng thử lại.');
            });
    });


    // Xóa bộ lọc
    document.getElementById('clearFiltersBtn').addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        document.getElementById('cccdFilter').value = '';
        document.getElementById('hoFilter').value = '';
        document.getElementById('tenFilter').value = '';
        document.getElementById('searchBtn').click(); // Tìm kiếm lại để hiển thị tất cả
    });

    // Chỉnh sửa học viên
    function editHocVien(id) {
        // Hiển thị loading
        const editBtn = event.target.closest('button');
        const originalContent = editBtn.innerHTML;
        editBtn.disabled = true;
        editBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        // Gọi API để lấy thông tin học viên
        fetch(`/hoc-vien/${id}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Không thể tải thông tin học viên');
                }
                return response.json();
            })
            .then(data => {
                // Format ngày tháng cho input type="date"
                const formatDateForInput = (dateString) => {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toISOString().split('T')[0];
                };

                document.getElementById('edit_ho').value = data.ho || '';
                document.getElementById('edit_ten').value = data.ten || '';
                document.getElementById('edit_ngay_sinh').value = formatDateForInput(data.ngay_sinh);
                document.getElementById('edit_cccd').value = data.cccd || '';
                document.getElementById('edit_dia_chi').value = data.dia_chi || '';
                document.getElementById('edit_khoa_hoc').value = data.khoa_hoc || '';
                document.getElementById('edit_noi_dung_thi').value = data.noi_dung_thi || '';
                document.getElementById('edit_ngay_sat_hach').value = formatDateForInput(data.ngay_sat_hach);
                document.getElementById('edit_dau_moi').value = data.dau_moi || '';
                document.getElementById('edit_ly_thuyet').value = data.ly_thuyet || 0;
                document.getElementById('edit_mo_phong').value = data.mo_phong || 0;
                document.getElementById('edit_thuc_hanh').value = data.thuc_hanh || 0;
                document.getElementById('edit_duong_truong').value = data.duong_truong || 0;
                document.getElementById('edit_ghi_chu').value = data.ghi_chu || '';

                document.getElementById('editForm').action = `/hoc-vien/${id}`;

                new bootstrap.Modal(document.getElementById('editModal')).show();
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Lỗi khi tải thông tin học viên: ' + error.message);
            })
            .finally(() => {
                // Reset button
                editBtn.disabled = false;
                editBtn.innerHTML = originalContent;
            });
    }

    // Cập nhật học viên
    function updateHocVien() {
        const form = document.getElementById('editForm');
        const updateBtn = document.getElementById('updateBtn');

        // Validation phía client
        const requiredFields = ['edit_ho', 'edit_ten', 'edit_ngay_sinh', 'edit_dia_chi',
            'edit_khoa_hoc', 'edit_noi_dung_thi', 'edit_ngay_sat_hach',
            'edit_dau_moi', 'edit_ly_thuyet', 'edit_mo_phong',
            'edit_thuc_hanh', 'edit_duong_truong'
        ];

        let isValid = true;
        let firstInvalidField = null;

        // Kiểm tra các trường bắt buộc
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Kiểm tra điểm số (0-100)
        const scoreFields = ['edit_ly_thuyet', 'edit_mo_phong', 'edit_thuc_hanh', 'edit_duong_truong'];
        scoreFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const value = parseInt(field.value);
            if (isNaN(value) || value < 0 || value > 100) {
                field.classList.add('is-invalid');
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Disable button và hiển thị loading
        updateBtn.disabled = true;
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...';

        // Submit form
        form.submit();
    }

    // Xóa học viên
    function deleteHocVien(id) {
        if (confirm('Bạn có chắc chắn muốn xóa học viên này?')) {
            const deleteBtn = event.target.closest('button');
            const originalContent = deleteBtn.innerHTML;

            // Disable button và hiển thị loading
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/hoc-vien/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Lỗi khi xóa học viên');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Lỗi khi xóa học viên');
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Lỗi khi xóa học viên: ' + error.message);
                })
                .finally(() => {
                    // Reset button
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = originalContent;
                });
        }
    }

    // Tải mẫu file XLSX
    function downloadTemplate() {
        // Tạo link tải file từ server
        const link = document.createElement('a');
        link.href = '/hoc-vien/download-template';
        link.download = 'mau_import_hoc_vien.xlsx';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function exportExcel() {
        var table = document.getElementById('hocVienTable');
        // Tạo một bảng tạm thời để xuất Excel (bỏ cột cuối cùng)
        var tempTable = table.cloneNode(true);
        // Xóa cột cuối cùng (cột Thao tác) khỏi tất cả các hàng
        var rows = tempTable.rows;
        for (var i = 0; i < rows.length; i++) {
            if (rows[i].cells.length > 0) {
                rows[i].deleteCell(rows[i].cells.length - 1);
            }
        }
        // Chuyển table tạm thời sang worksheet
        var ws = XLSX.utils.table_to_sheet(tempTable);

        // Tính độ rộng cột tự động theo nội dung
        var colWidths = [];
        var numCols = tempTable.rows[0].cells.length;
        // Khởi tạo độ rộng cột dựa trên header
        for (var i = 0; i < numCols; i++) {
            var headerText = tempTable.rows[0].cells[i].textContent || tempTable.rows[0].cells[i].innerText;
            colWidths[i] = Math.max(headerText.length, 8); // Tối thiểu 8
        }
        // Duyệt qua tất cả các hàng để tìm độ rộng tối đa
        for (var row = 1; row < tempTable.rows.length; row++) {
            for (var col = 0; col < tempTable.rows[row].cells.length; col++) {
                var cellText = tempTable.rows[row].cells[col].textContent || tempTable.rows[row].cells[col].innerText;
                // Tính độ rộng dựa trên số ký tự (giảm hệ số để cột ngắn hơn)
                var cellWidth = Math.ceil(cellText.length * 1.2);
                colWidths[col] = Math.max(colWidths[col], cellWidth);
            }
        }
        // Giới hạn độ rộng tối đa là 25 để tránh quá rộng
        for (var i = 0; i < colWidths.length; i++) {
            colWidths[i] = Math.min(colWidths[i], 25);
        }
        // Áp dụng độ rộng cột và căn trái
        ws['!cols'] = colWidths.map(function(width) {
            return { wch: width };
        });

        // Duyệt qua từng cell, căn trái tất cả dữ liệu và xử lý số lớn
        var range = XLSX.utils.decode_range(ws['!ref']);
        for (var R = range.s.r; R <= range.e.r; ++R) { // bao gồm cả header
            for (var C = range.s.c; C <= range.e.c; ++C) {
                var cell_address = {c:C, r:R};
                var cell_ref = XLSX.utils.encode_cell(cell_address);
                var cell = ws[cell_ref];
                if (cell) {
                    // Đảm bảo căn trái cho tất cả ô
                    if (!cell.s) cell.s = {};
                    if (!cell.s.alignment) cell.s.alignment = {};
                    cell.s.alignment.horizontal = 'left';
                    cell.s.alignment.vertical = 'center';
                    // Xử lý số lớn để không bị e+12
                    if (cell.t === 'n') {
                        if (Math.abs(cell.v) > 999999999) {
                            cell.t = 's';
                            cell.v = cell.v.toString();
                        }
                    }
                }
            }
        }

        // Thiết lập style mặc định cho worksheet để đảm bảo căn trái
        if (!ws['!rows']) ws['!rows'] = [];
        for (var i = 0; i <= range.e.r; i++) {
            ws['!rows'][i] = { hpt: 20}; // Chiều cao hàng
        }

        // Tạo workbook và xuất file
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "DanhSachHocVien");
        XLSX.writeFile(wb, 'danh_sach_hoc_vien.xlsx');
    }
    
    // Hiển thị modal thông báo lỗi
    function showSuccessModal(title, message) {
        const errorContent = document.getElementById('errorContent');
        errorContent.innerHTML = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle me-2"></i>${title}</h6>
            <div class="mt-3">
                ${message}
            </div>
        </div>
    `;

        // Hiển thị modal thành công đè lên modal import
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {
            backdrop: 'static',
            keyboard: false
        });
        errorModal.show();

        // Tự động đóng modal thành công sau 5 giây
        setTimeout(() => {
            if (errorModal._element.classList.contains('show')) {
                errorModal.hide();
            }
        }, 5000);
    }

    function showErrorModal(title, message) {
        const errorContent = document.getElementById('errorContent');
        errorContent.innerHTML = `
        <div class="alert alert-danger">
            <h6><i class="fas fa-times-circle me-2"></i>${title}</h6>
            <div class="mt-3">
                ${message}
            </div>
        </div>
    `;

        // Hiển thị modal lỗi đè lên modal import
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {
            backdrop: 'static',
            keyboard: false
        });
        errorModal.show();

        // Tự động đóng modal lỗi sau 10 giây nếu người dùng không đóng
        setTimeout(() => {
            if (errorModal._element.classList.contains('show')) {
                errorModal.hide();
            }
        }, 10000);
    }

    // Reset form khi modal đóng
    document.getElementById('editModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('editForm');
        form.reset();

        // Xóa tất cả class validation
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });

        // Reset button
        const updateBtn = document.getElementById('updateBtn');
        updateBtn.disabled = false;
        updateBtn.innerHTML = '<i class="fas fa-save me-2"></i>Cập nhật';
    });

    // Tự động focus vào trường đầu tiên khi modal mở
    document.getElementById('editModal').addEventListener('shown.bs.modal', function () {
        document.getElementById('edit_ho').focus();
    });

</script>
@endsection
