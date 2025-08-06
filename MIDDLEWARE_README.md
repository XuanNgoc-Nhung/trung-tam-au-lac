# Middleware LoginAdmin - Hướng dẫn sử dụng

## Tổng quan
Middleware `loginAdmin` được tạo để bảo vệ các route admin, yêu cầu người dùng phải đăng nhập với quyền admin trước khi có thể truy cập.

## Cách hoạt động

### 1. Kiểm tra đăng nhập
Middleware kiểm tra session `admin_logged_in` để xác định người dùng đã đăng nhập hay chưa.

### 2. Kiểm tra quyền
Middleware cũng kiểm tra session `user_role` để đảm bảo người dùng có quyền admin.

### 3. Chuyển hướng
- Nếu chưa đăng nhập: Chuyển hướng về trang login
- Nếu không có quyền admin: Chuyển hướng về trang chủ

## Cách sử dụng

### 1. Đăng ký middleware
Middleware đã được đăng ký trong `bootstrap/app.php`:
```php
$middleware->alias([
    'loginAdmin' => \App\Http\Middleware\LoginAdmin::class,
]);
```

### 2. Áp dụng middleware cho routes
```php
Route::group(['prefix' => 'admin', 'middleware' => 'loginAdmin'], function () {
    Route::get('/', [NhapLieuController::class, 'admin'])->name('admin');
    Route::get('/cau-hinh', [NhapLieuController::class, 'cauHinh'])->name('cau-hinh.index');
});
```

### 3. Thông tin đăng nhập
- **Username**: `admin`
- **Password**: `admin123`

## Các file đã tạo/cập nhật

### 1. Middleware
- `app/Http/Middleware/LoginAdmin.php` - Middleware chính

### 2. Controller
- `app/Http/Controllers/NhapLieuController.php` - Thêm methods:
  - `processLogin()` - Xử lý đăng nhập
  - `logout()` - Xử lý đăng xuất

### 3. Routes
- `routes/web.php` - Thêm routes:
  - `POST /login` - Xử lý đăng nhập
  - `GET /logout` - Xử lý đăng xuất

### 4. Views
- `resources/views/login.blade.php` - Form đăng nhập đẹp
- `resources/views/layouts/app.blade.php` - Thêm nút đăng xuất

### 5. Cấu hình
- `bootstrap/app.php` - Đăng ký middleware

## Cách test

1. Truy cập trang admin: `http://localhost/quanLyKhoaHoc/admin`
2. Bạn sẽ được chuyển hướng đến trang login
3. Đăng nhập với username: `admin`, password: `admin123`
4. Sau khi đăng nhập thành công, bạn có thể truy cập trang admin
5. Sử dụng nút đăng xuất trong dropdown menu để đăng xuất

## Tùy chỉnh

### Thay đổi thông tin đăng nhập
Trong `NhapLieuController.php`, method `processLogin()`:
```php
if ($request->username === 'admin' && $request->password === 'admin123') {
    // Thay đổi thông tin đăng nhập ở đây
}
```

### Thêm kiểm tra database
Bạn có thể thay thế logic kiểm tra cứng bằng kiểm tra database:
```php
$user = User::where('username', $request->username)->first();
if ($user && Hash::check($request->password, $user->password)) {
    session(['admin_logged_in' => true]);
    session(['user_role' => $user->role]);
    return redirect()->route('admin');
}
```

## Lưu ý bảo mật

1. **Mật khẩu**: Nên sử dụng hash password thay vì plain text
2. **Session**: Có thể thêm timeout cho session
3. **Rate limiting**: Nên thêm giới hạn số lần đăng nhập thất bại
4. **HTTPS**: Nên sử dụng HTTPS trong production

## Troubleshooting

### Lỗi "Class not found"
- Kiểm tra namespace trong `LoginAdmin.php`
- Chạy `composer dump-autoload`

### Middleware không hoạt động
- Kiểm tra đăng ký middleware trong `bootstrap/app.php`
- Kiểm tra tên middleware trong routes

### Session không lưu
- Kiểm tra cấu hình session trong `config/session.php`
- Đảm bảo storage có quyền ghi 