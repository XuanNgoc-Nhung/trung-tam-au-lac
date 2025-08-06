<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu khóa học</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .search-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .search-form {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        .form-group {
            flex: 1;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Tra cứu thi</h4>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('tra-cuu') }}" class="search-form">
                                <div class="form-group">
                                    <label for="cccd" class="form-label">Nhập số CCCD:</label>
                                    <input type="text" value="{{ request()->get('cccd') }}" class="form-control" id="cccd" name="cccd" required 
                                           placeholder="Nhập số CCCD của bạn">
                                </div>
                                <button type="submit" class="btn btn-primary">Tra cứu</button>
                                <a href="/" class="btn btn-secondary">Về trang chủ</a>
                            </form>

                            @if(isset($satHaches) && count($satHaches) > 0)
                                <div class="mt-4">
                                    <h5>Danh sách khóa học đã đăng ký:</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">STT</th>
                                                    <th>Họ và tên</th>
                                                    <th>CCCD</th>
                                                    <th class="text-center">Ngày sinh</th>
                                                    <th>Giáo viên</th>
                                                    <th class="text-center">Ngày thi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($satHaches as $satHach)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>{{ $satHach->hocVien->ho . ' ' . $satHach->hocVien->ten }}</td>
                                                        <td>{{ $satHach->cccd }}</td>
                                                        <td class="text-center">{{ $satHach->hocVien->ngay_sinh }}</td>
                                                        <td>{{ $satHach->hocVien->dau_moi }}</td>
                                                        <td class="text-center">{{ $satHach->ngay_thi }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif(request()->has('cccd'))
                                <div class="alert alert-info mt-4">
                                    Chưa có dữ liệu cho CCCD này.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
