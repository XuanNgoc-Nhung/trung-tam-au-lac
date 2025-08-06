<footer class="footer">
    <div class="footer-content">
        <div class="row">
            <div class="col-md-6">
                {{-- <p class="mb-0">
                    <i class="fas fa-copyright me-1"></i>
                    @yield('footer-copyright', '2024 Hệ thống Quản lý Khóa học. Tất cả quyền được bảo lưu.')
                </p> --}}
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">
                    <i class="fas fa-code me-1"></i>
                    @yield('footer-developer', 'Phát triển bởi <strong>Team Development</strong>')
                </p>
            </div>
        </div>
        @yield('footer-additional')
    </div>
</footer> 