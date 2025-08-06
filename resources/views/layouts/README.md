# Hướng Dẫn Sử Dụng Layout

## Cấu Trúc Layout Mới

Layout đã được tái cấu trúc để dễ dàng mở rộng và tái sử dụng:

### 1. Layout Chính (`layouts/app.blade.php`)
- Chứa header, sidebar, và cấu trúc cơ bản
- Sử dụng flexbox để đảm bảo footer luôn ở cuối trang
- Có thể tùy chỉnh footer thông qua `@section('footer')`

### 2. Layout Content (`layouts/content.blade.php`)
- Extends từ `layouts/app.blade.php`
- Chứa content-wrapper với page header, alerts, và breadcrumbs
- Tự động hiển thị thông báo success/error
- Tự động hiển thị validation errors

### 3. Layout Footer (`layouts/footer.blade.php`)
- Chứa cấu trúc footer mặc định
- Có thể tùy chỉnh thông qua các sections:
  - `@section('footer-copyright')` - Thông tin bản quyền
  - `@section('footer-developer')` - Thông tin developer
  - `@section('footer-additional')` - Nội dung bổ sung

## Cách Sử Dụng

### Sử dụng layout content (khuyến nghị):
```php
@extends('layouts.content')

@section('title', 'Tiêu đề trang')

@section('content')
    {{-- Nội dung trang --}}
@endsection

{{-- Tùy chỉnh footer (tùy chọn) --}}
@section('footer')
    {{-- Footer tùy chỉnh --}}
@endsection
```

### Sử dụng layout chính trực tiếp:
```php
@extends('layouts.app')

@section('content-wrapper')
    {{-- Nội dung tùy chỉnh --}}
@endsection

@section('footer')
    {{-- Footer tùy chỉnh --}}
@endsection
```

### Tùy chỉnh footer với layout footer:
```php
@extends('layouts.content')

@section('footer-copyright', 'Copyright 2024 - Custom Text')
@section('footer-developer', 'Developed by <strong>Custom Team</strong>')

@section('footer-additional')
<div class="row mt-3">
    <div class="col-12 text-center">
        <small>Additional footer content</small>
    </div>
</div>
@endsection
```

## Các Section Có Sẵn

### Trong Layout App:
- `@section('title')` - Tiêu đề trang
- `@section('content-wrapper')` - Nội dung chính
- `@section('footer')` - Footer tùy chỉnh

### Trong Layout Content:
- `@section('content')` - Nội dung trang
- Tất cả sections từ layout app

### Trong Layout Footer:
- `@section('footer-copyright')` - Thông tin bản quyền
- `@section('footer-developer')` - Thông tin developer
- `@section('footer-additional')` - Nội dung bổ sung

## Biến Có Sẵn

### Page Header:
- `$pageTitle` - Tiêu đề trang
- `$breadcrumbs` - Mảng breadcrumbs với format:
  ```php
  [
      ['title' => 'Trang chủ', 'url' => '/'],
      ['title' => 'Trang hiện tại']
  ]
  ```

### Alerts:
- Tự động hiển thị `session('success')`
- Tự động hiển thị `session('error')`
- Tự động hiển thị validation errors từ `$errors`

## Ví Dụ Controller

```php
public function index()
{
    return view('pages.example', [
        'pageTitle' => 'Trang Ví Dụ',
        'breadcrumbs' => [
            ['title' => 'Trang chủ', 'url' => route('home')],
            ['title' => 'Trang Ví Dụ']
        ]
    ]);
}
``` 