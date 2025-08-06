@extends('layouts.app')
@section('content')
<div class="container" style="width: 100% !important;margin:0 !important;padding:0 !important; max-width: 100% !important;">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách học viên đã đăng ký sát hạch</h3>
                </div>
                <div class="card-body">

                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc tìm kiếm</h6>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="{{ route('sat-hach.index') }}" id="filterForm">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="keyword" class="form-label">Từ khóa tìm kiếm</label>
                                                <input type="text" class="form-control" id="keyword" name="keyword" 
                                                       placeholder="Nhập họ tên, CCCD..." 
                                                       value="{{ request('keyword') }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="ngay_sat_hach" class="form-label">Ngày sát hạch</label>
                                                <input type="date" class="form-control" id="ngay_sat_hach" name="ngay_sat_hach" 
                                                       value="{{ request('ngay_sat_hach') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label style="display: block; margin-bottom: 8px; color: transparent;">Ngày đăng ký</label>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Tìm kiếm
                                                </button>
                                                <button type="button" class="btn btn-success" onclick="exportExcel()">
                                                    <i class="fas fa-file-excel"></i> Xuất Excel
                                                </button>
                                                <a href="{{ route('sat-hach.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i> Xóa bộ lọc
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="satHachTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>STT</th>
                                    <th>Họ và tên</th>
                                    <th>Số báo danh</th>
                                    <th>Ngày sinh</th>
                                    <th>Khóa học</th>
                                    <th>Nội dung thi</th>
                                    <th>Ngày sát hạch</th>
                                    <th>Đầu mối</th>
                                    <th>Lý thuyết</th>
                                    <th>Mô phỏng</th>
                                    <th>Thực hành</th>
                                    <th>Đường trường</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Học phí</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($satHaches as $index => $satHach)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($satHach->hocVien)
                                        {{ $satHach->hocVien->ho }} {{ $satHach->hocVien->ten }}
                                        @else
                                        <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </td>
                                    <td>{{ $satHach->cccd }}</td>
                                    <td>
                                        @if($satHach->hocVien && $satHach->hocVien->ngay_sinh)
                                        {{ \Carbon\Carbon::parse($satHach->hocVien->ngay_sinh)->format('d/m/Y') }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($satHach->hocVien)
                                        {{ $satHach->hocVien->khoa_hoc ?? '-' }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($satHach->hocVien)
                                        {{ $satHach->hocVien->noi_dung_thi ?? '-' }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($satHach->hocVien && $satHach->hocVien->ngay_sat_hach)
                                        {{ \Carbon\Carbon::parse($satHach->hocVien->ngay_sat_hach)->format('d/m/Y') }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($satHach->hocVien)
                                        {{ $satHach->hocVien->dau_moi ?? '-' }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($satHach->hocVien)
                                        {{ $satHach->hocVien->ly_thuyet ?? '-' }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($satHach->hocVien)
                                        {{ $satHach->hocVien->mo_phong ?? '-' }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($satHach->hocVien)
                                        {{ $satHach->hocVien->thuc_hanh ?? '-' }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($satHach->hocVien)
                                        {{ $satHach->hocVien->duong_truong ?? '-' }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($satHach->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                    <p>{{ $satHach->ghi_chu }}</p>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i> Không có học viên nào đăng ký sát hạch
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <i class="fas fa-chart-bar"></i>
                            <strong>Tổng số học viên đã đăng ký: {{ $satHaches->count() }}</strong>
                            @if(request('keyword') || request('ngay_sat_hach') || request('ngay_thi'))
                                <br><small class="text-muted">
                                    <i class="fas fa-filter"></i> 
                                    Kết quả tìm kiếm với bộ lọc:
                                    @if(request('keyword')) <span class="badge bg-primary">Từ khóa: {{ request('keyword') }}</span> @endif
                                    @if(request('ngay_sat_hach')) <span class="badge bg-info">Ngày sát hạch: {{ request('ngay_sat_hach') }}</span> @endif
                                    @if(request('ngay_thi')) <span class="badge bg-warning">Ngày thi: {{ request('ngay_thi') }}</span> @endif
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .table th {
        background-color: #343a40;
        color: white;
        border-color: #454d55;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .btn {
        margin-right: 5px;
    }

    .alert {
        border-radius: 0.25rem;
    }

</style>
@endsection

<!-- Nhúng thư viện SheetJS và script xuất Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportExcel() {
        var table = document.getElementById('satHachTable');
        // Chuyển table sang worksheet
        var ws = XLSX.utils.table_to_sheet(table);

        // Tính độ rộng cột tự động theo nội dung
        var colWidths = [];
        var numCols = table.rows[0].cells.length;
        // Khởi tạo độ rộng cột dựa trên header
        for (var i = 0; i < numCols; i++) {
            var headerText = table.rows[0].cells[i].textContent || table.rows[0].cells[i].innerText;
            colWidths[i] = Math.max(headerText.length, 8); // Tối thiểu 8
        }
        // Duyệt qua tất cả các hàng để tìm độ rộng tối đa
        for (var row = 1; row < table.rows.length; row++) {
            for (var col = 0; col < table.rows[row].cells.length; col++) {
                var cellText = table.rows[row].cells[col].textContent || table.rows[row].cells[col].innerText;
                // Tính độ rộng dựa trên số ký tự (giảm hệ số để cột ngắn hơn)
                var cellWidth = Math.ceil(cellText.length * 0.8);
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
        XLSX.utils.book_append_sheet(wb, ws, "DanhSachSatHach");
        XLSX.writeFile(wb, 'danh_sach_sat_hach.xlsx');
    }
</script>
