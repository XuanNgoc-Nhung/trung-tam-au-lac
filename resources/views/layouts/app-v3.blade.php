<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản Lý Khóa Học')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
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
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-weight: 400;
            line-height: 1.6;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            height: 60px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            font-family: 'Times New Roman', Times, serif;
        }

        .logo:hover {
            color: var(--accent-color);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Times New Roman', Times, serif;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 60px;
            width: var(--sidebar-width);
            height: calc(100vh - 60px);
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            background: linear-gradient(135deg, var(--accent-color), #2980b9);
            color: white;
        }

        .sidebar-menu {
            padding: 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #f8f9fa;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 12px;
            font-family: 'Times New Roman', Times, serif;
            font-weight: 400;
        }

        .sidebar-menu a:hover {
            background-color: var(--accent-color);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-menu a.active {
            background-color: var(--accent-color);
            color: white;
            border-left: 4px solid var(--success-color);
        }

        .sidebar-menu i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 60px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .content-area {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .content-wrapper {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .page-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-color);
        }

        .page-title {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            font-family: 'Times New Roman', Times, serif;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 10px 0 0 0;
        }

        /* Footer */
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 20px 0;
            margin-top: auto;
        }

        .footer-content {
            padding: 0 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header-content {
                padding: 0 15px;
            }

            .content-wrapper {
                padding: 20px;
            }

            .footer-content {
                padding: 0 15px;
            }
        }

        /* Toggle Button for Mobile */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--accent-color), #2980b9);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        /* Button Styles */
        .btn-primary {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background: #2980b9;
            border-color: #2980b9;
        }

        /* Table Styles */
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .table thead th {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        /* Alert Styles */
        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Form Styles */
        .form-control {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            font-family: 'Times New Roman', Times, serif;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Typography optimization for Times New Roman */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Times New Roman', Times, serif;
            font-weight: 700;
        }

        p, span, div {
            font-family: 'Times New Roman', Times, serif;
        }

        .btn {
            font-family: 'Times New Roman', Times, serif;
        }

        .table {
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle me-3">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('home') }}" class="logo">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Quản Lý Khóa Học
                </a>
            </div>
            
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="d-none d-md-block">
                        <div class="fw-bold">Admin</div>
                        <small class="text-light">Quản trị viên</small>
                    </div>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-link text-white" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h6 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Bảng điều khiển</h6>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Trang chủ</span>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('sat-hach.index') }}" class="{{ request()->routeIs('sat-hach.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Sát hạch</span>
                </a>
            </li>
            <li>
                <a href="{{ route('dau-moi.index') }}" class="{{ request()->routeIs('dau-moi.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Đầu mối</span>
                </a>
            </li>
            <li>
                <a href="{{ route('nhap-lieu') }}" class="{{ request()->routeIs('nhap-lieu') ? 'active' : '' }}">
                    <i class="fas fa-edit"></i>
                    <span>Học viên</span>
                </a>
            </li> --}}
            <li>
                <a href="{{ route('hoc-phi.index') }}" class="{{ request()->routeIs('hoc-phi.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill"></i>
                    <span>Học phí</span>
                </a>
            </li>
            <li>
                <a href="{{ route('cau-hinh.index') }}" class="{{ request()->routeIs('cau-hinh.*') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i>
                    <span>Cấu hình</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @hasSection('footer')
        @yield('footer')
    @else
        @include('layouts.footer')
    @endif

    <!-- Axios for HTTP requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SheetJS for Excel processing -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
