<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Quản Lý Khóa Học</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row min-vh-100 align-items-center justify-content-center">
                <div class="col-md-8 text-center">
                    <h1 class="mb-5">Trung tâm Âu Lạc</h1>
                    <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                        <a href="{{ route('dang-ky') }}" class="btn btn-info btn-lg px-4">Đăng ký</a>
                        <a href="{{ route('tra-cuu') }}" class="btn btn-success btn-lg px-4">Tra cứu</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
