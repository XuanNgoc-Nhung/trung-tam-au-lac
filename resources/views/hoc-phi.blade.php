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
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" name="sbd" placeholder="Số báo danh" value="{{ request('sbd') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" name="ngay_thi" placeholder="Ngày thi" value="{{ request('ngay_thi') }}">
                                    </div>
                                </div>
                                 <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" name="tu_khoa" placeholder="CCCD, họ hoặc tên" value="{{ request('tu_khoa') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="trang_thai" id="trangThaiFilter">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="da_thanh_toan" {{ request('trang_thai') == 'da_thanh_toan' ? 'selected' : '' }}>Đã thanh toán</option>
                                        <option value="chua_thanh_toan" {{ request('trang_thai') == 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" type="submit" title="Tìm kiếm">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                    <button type="button" class="btn btn-success" id="exportExcelBtn" title="Xuất Excel" onclick="exportExcel()">
                                        <i class="fas fa-file-excel"></i> Xuất Excel
                                    </button>
                                    <a href="{{ url()->current() }}" class="btn btn-danger" title="Xóa bộ lọc">
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
                            <th>Số báo danh</th>
                            <th>Ngày thi</th>
                            <th>Họ và tên</th>
                            <th>CMND</th>
                            <th>Ngày sinh</th>
                            <th>Địa chỉ</th>
                            <th>Hạng</th>
                            <th>Đầu mối</th>
                            <th>Lệ phí</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hocViens ?? [] as $index => $hocVien)
                        <tr>            
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $hocVien->sbd ?? 'Chưa có' }}
                                </span>
                            </td>
                            <td>{{ $hocVien->ngay_thi }}</td>
                            <td>{{ $hocVien->ho_va_ten }}</td>
                            <td>{{ $hocVien->so_cccd ?? 'Không có'}}</td>
                            <td>{{ \Carbon\Carbon::parse($hocVien->ngay_sinh)->format('d/m/Y') }}</td>
                            <td>{{ $hocVien->dia_chi }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $hocVien->hang ?? 'Chưa xác định' }}
                                </span>
                            </td>
                            <td>{{ $hocVien->dau_moi }}</td>
                            <td>
                                <span class="text-success fw-bold">
                                    {{ number_format($hocVien->le_phi ?? 0, 0, ',', '.') }} VNĐ
                                </span>
                            </td>
                            <td>
                                @php
                                    $trangThai = $hocVien->trang_thai ?? 'Chưa thanh toán';
                                    $badgeClass = ($trangThai === 'Đã thanh toán') ? 'bg-success' : 'bg-warning';
                                    $isDaThanhToan = ($trangThai === 'Đã thanh toán');
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $trangThai }}
                                </span>
                            </td>
                            <td>
                                {{-- <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="editHocVien({{ $hocVien->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteHocVien({{ $hocVien->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div> --}}
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="da_thanh_toan_{{ $hocVien->id }}" 
                                               {{ $isDaThanhToan ? 'checked' : '' }}
                                               onchange="updateTrangThai({{ $hocVien->id }}, this.checked)">
                                        <label class="form-check-label" for="da_thanh_toan_{{ $hocVien->id }}">
                                            <span class="badge {{ $isDaThanhToan ? 'bg-success' : 'bg-warning' }}">
                                                {{ $isDaThanhToan ? 'Đã TT' : 'Chưa TT' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">Chưa có dữ liệu học viên</td>
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
                                <label for="edit_khoa_hoc" class="form-label">Hạng</label>
                                <input type="text" class="form-control" id="edit_khoa_hoc" name="khoa_hoc" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_dau_moi" class="form-label">Đầu mối</label>
                                <input type="text" class="form-control" id="edit_dau_moi" name="dau_moi" required>
                            </div>
                        </div>
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
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_hoc_phi" class="form-label">Lệ phí (VNĐ)</label>
                                <input type="number" class="form-control" id="edit_hoc_phi" name="hoc_phi"
                                    min="0" step="1000" required>
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
                                        <div class="form-text">Hỗ trợ định dạng .xlsx, .xls và .csv</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Cấu trúc file Excel</label>
                                        <div class="alert alert-info">
                                            <p class="mb-1"><strong>Dữ liệu sẽ được đọc từ hàng thứ 2 (bỏ qua hàng tiêu
                                                    đề) với các cột:</strong></p>
                                            <ul class="mb-0">
                                                <li>Số báo danh, Họ và tên, Ngày sinh, CCCD, Địa chỉ</li>
                                                <li>Hạng, Đầu mối, Lệ phí</li>
                                                <li>Trạng thái thanh toán (Đã thanh toán/Chưa thanh toán)</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="previewBtn">
                                            <i class="fas fa-eye me-2"></i>Xem trước dữ liệu
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="downloadTemplate()">
                                            <i class="fas fa-download me-2"></i>Tải mẫu CSV
                                        </button>
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
                            <div class="d-flex w-25 text-right">
                                <input type="text" class="form-control-sm" placeholder="dd/mm/yyyy" id="ngayThiImport" name="ngay_thi" required>
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
                                            <th>Số báo danh</th>
                                            <th>Họ và tên</th>
                                            <th>Ngày sinh</th>
                                            <th>CCCD</th>
                                            <th>Địa chỉ</th>
                                            <th>Hạng</th>
                                            <th>Đầu mối</th>
                                            <th>Lệ phí</th>
                                            <th>Trạng thái thanh toán</th>
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

    // Xử lý form xem trước Excel
    document.getElementById('importForm').addEventListener('submit', function (e) {
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
                        String(row[0]).toLowerCase().includes('số báo danh') ||
                        String(row[0]).toLowerCase().includes('so bao danh') ||
                        String(row[1]).toLowerCase().includes('họ và tên') ||
                        String(row[1]).toLowerCase().includes('ho va ten') ||
                        String(row[2]).toLowerCase().includes('ngày sinh') ||
                        String(row[2]).toLowerCase().includes('ngay sinh') ||
                        String(row[3]).toLowerCase().includes('cccd') ||
                        String(row[3]).toLowerCase().includes('cmnd') ||
                        String(row[8]).toLowerCase().includes('trạng thái') ||
                        String(row[8]).toLowerCase().includes('trang thai')
                    );

                    // Chỉ xử lý các hàng không phải tiêu đề và có dữ liệu
                    if (row && row.some(cell => cell !== null && cell !== '') && !isHeaderRow) {
                        actualDataIndex++; // Tăng số thứ tự thực tế
                        // Xử lý trạng thái thanh toán từ cột thứ 9 (index 8)
                        const trangThaiThanhToan = String(row[8] || '').toLowerCase().trim();
                        const daThanhToan = trangThaiThanhToan === 'đã thanh toán' || trangThaiThanhToan === 'da thanh toan' || trangThaiThanhToan === '1' || trangThaiThanhToan === 'true';
                        
                        // Lấy số báo danh từ cột đầu tiên
                        const soBaoDanh = String(row[0] || '').trim();
                        // Lấy họ và tên từ cột thứ 2
                        const hoVaTen = String(row[1] || '').trim();
                        
                        dataRows.push({
                            'row_number': index +
                            2, // Số thứ tự hàng trong Excel (bắt đầu từ hàng 2)
                            'display_index': actualDataIndex, // Số thứ tự hiển thị trong bảng xem trước
                            'sbd': soBaoDanh, // Số báo danh
                            'ho_va_ten': hoVaTen,
                            'ngay_sinh': String(row[2] || ''),
                            'cccd': String(row[3] || ''),
                            'dia_chi': String(row[4] || ''),
                            'khoa_hoc': String(row[5] || ''),
                            'dau_moi': String(row[6] || ''),
                            'hoc_phi': String(row[7] || '0'),
                            'da_thanh_toan': daThanhToan
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
                        'File không chứa dữ liệu hợp lệ hoặc tất cả các hàng đều bị bỏ qua. Vui lòng kiểm tra lại file.<br><br><strong>Lưu ý:</strong> File Excel/CSV phải có cấu trúc: Số báo danh, Họ và tên, Ngày sinh, CCCD, Địa chỉ, Hạng, Đầu mối, Lệ phí, Trạng thái thanh toán'
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
            <td>
                <span class="badge bg-primary">
                    ${row.sbd || 'Chưa có'}
                </span>
            </td>
            <td>${row.ho_va_ten || ''}</td>
            <td>${row.ngay_sinh || ''}</td>
            <td>${row.cccd || ''}</td>
            <td>${row.dia_chi || ''}</td>
            <td>${row.khoa_hoc || ''}</td>
            <td>${row.dau_moi || ''}</td>
            <td>
                <span class="text-success fw-bold">
                    ${parseInt(row.hoc_phi || 0).toLocaleString('vi-VN')} VNĐ
                </span>
            </td>
            <td>
                <span class="badge ${row.da_thanh_toan ? 'bg-success' : 'bg-warning'}">
                    ${row.da_thanh_toan ? 'Đã thanh toán' : 'Chưa thanh toán'}
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
        const ngayThi = document.getElementById('ngayThiImport').value;
        // Gửi dữ liệu JSON để import
        if(!ngayThi){
            showErrorModal('Lỗi nhập liệu!', 'Vui lòng chọn ngày thi trước khi import.');
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
        fetch('/hoc-vien/import-json', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                
                body: JSON.stringify({
                    data: previewData,
                    ngay_thi: ngayThi?ngayThi:null
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


    // // Xóa bộ lọc
    // document.getElementById('clearFiltersBtn').addEventListener('click', function () {
    //     document.getElementById('searchInput').value = '';
    //     document.getElementById('cccdFilter').value = '';
    //     document.getElementById('hoFilter').value = '';
    //     document.getElementById('tenFilter').value = '';
    //     document.getElementById('searchBtn').click(); // Tìm kiếm lại để hiển thị tất cả
    // });

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
                document.getElementById('edit_dau_moi').value = data.dau_moi || '';
                document.getElementById('edit_ly_thuyet').value = data.ly_thuyet || 0;
                document.getElementById('edit_mo_phong').value = data.mo_phong || 0;
                document.getElementById('edit_thuc_hanh').value = data.thuc_hanh || 0;
                document.getElementById('edit_duong_truong').value = data.duong_truong || 0;
                document.getElementById('edit_hoc_phi').value = data.hoc_phi || 0;

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
            'edit_khoa_hoc', 'edit_dau_moi', 'edit_ly_thuyet', 'edit_mo_phong',
            'edit_thuc_hanh', 'edit_duong_truong', 'edit_hoc_phi'
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

        if (!isValid) {
            if (firstInvalidField) {
                firstInvalidField.focus();
            }
            return;
        }

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

    // Tải mẫu file CSV
    function downloadTemplate() {
        const template = `Số báo danh,Họ và tên,Ngày sinh,CCCD,Địa chỉ,Hạng,Đầu mối,Lệ phí,Trạng thái thanh toán
001,Nguyễn Văn A,01/01/2000,1234567890123,123 Đường ABC Quận 1 TP.HCM,B2,Đầu mối 1,5000000,Đã thanh toán
002,Trần Thị B,02/02/2001,9876543210987,456 Đường XYZ Quận 2 TP.HCM,B1,Đầu mối 2,6000000,Đã thanh toán
003,Lê Văn C,15/03/1995,1112223334445,789 Đường DEF Quận 3 TP.HCM,A1,Đầu mối 3,7500000,Chưa thanh toán
004,Phạm Thị D,20/04/1998,5556667778889,321 Đường GHI Quận 4 TP.HCM,B2,Đầu mối 1,5500000,Đã thanh toán
005,Hoàng Văn E,10/05/1997,9998887776665,654 Đường JKL Quận 5 TP.HCM,A2,Đầu mối 2,8000000,Chưa thanh toán
006,Võ Thị F,25/06/1996,3334445556667,987 Đường MNO Quận 6 TP.HCM,B1,Đầu mối 3,7000000,Đã thanh toán
007,Đặng Văn G,05/07/1999,7778889990001,147 Đường PQR Quận 7 TP.HCM,A1,Đầu mối 1,9000000,Chưa thanh toán
008,Bùi Thị H,30/08/1994,2223334445556,258 Đường STU Quận 8 TP.HCM,B2,Đầu mối 2,6500000,Đã thanh toán
009,Dương Văn I,12/09/1993,8889990001112,369 Đường VWX Quận 9 TP.HCM,A2,Đầu mối 3,8500000,Chưa thanh toán
010,Ngô Thị K,18/10/1992,4445556667778,741 Đường YZA Quận 10 TP.HCM,B1,Đầu mối 1,5500000,Chưa thanh toán`;
        const blob = new Blob([template], {
            type: 'text/csv;charset=utf-8'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'mau_import_hoc_vien.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    function exportExcel() {
        var table = document.getElementById('hocVienTable');
        // Tạo một bảng tạm thời để xuất Excel (bỏ cột cuối cùng - cột Thao tác)
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

    // Cập nhật trạng thái thanh toán
    function updateTrangThai(hocVienId, daThanhToan) {
        // Hiển thị loading trên checkbox
        const checkbox = document.querySelector(`input[id="da_thanh_toan_${hocVienId}"]`);
        const originalLabel = checkbox.nextElementSibling.innerHTML;
        checkbox.nextElementSibling.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        checkbox.disabled = true;

        // Gọi API để cập nhật trạng thái
        fetch(`/hoc-vien/${hocVienId}/update-trang-thai`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                da_thanh_toan: daThanhToan
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Lỗi khi cập nhật trạng thái');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Hiển thị thông báo thành công
                showSuccessMessage('Cập nhật trạng thái thanh toán thành công!');
                setTimeout(() => {
                    location.reload();
                }, 500);
                // Cập nhật badge trạng thái trong bảng
                const statusCell = checkbox.closest('tr').querySelector('td:nth-child(9)');
                if (daThanhToan) {
                    statusCell.innerHTML = '<span class="badge bg-success">Đã thanh toán</span>';
                } else {
                    statusCell.innerHTML = '<span class="badge bg-warning">Chưa thanh toán</span>';
                }
                
                // Cập nhật badge trong checkbox label
                // const badge = checkbox.nextElementSibling.querySelector('.badge');
                // if (daThanhToan) {
                //     badge.className = 'badge bg-success';
                //     badge.textContent = 'Đã TT';
                // } else {
                //     badge.className = 'badge bg-warning';
                //     badge.textContent = 'Chưa TT';
                // }
            } else {
                throw new Error(data.message || 'Lỗi khi cập nhật trạng thái');
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            
            // Khôi phục trạng thái checkbox
            checkbox.checked = !daThanhToan;
            
            showErrorMessage('Lỗi khi cập nhật trạng thái: ' + error.message);
        })
        .finally(() => {
            // Khôi phục label và enable checkbox
            checkbox.nextElementSibling.innerHTML = originalLabel;
            checkbox.disabled = false;
        });
    }

    // Hiển thị thông báo thành công
    function showSuccessMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }

    // Hiển thị thông báo lỗi
    function showErrorMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Xử lý bộ lọc trạng thái
    document.getElementById('trangThaiFilter').addEventListener('change', function() {
        // Tự động submit form khi thay đổi trạng thái
        this.closest('form').submit();
    });

</script>
@endsection
