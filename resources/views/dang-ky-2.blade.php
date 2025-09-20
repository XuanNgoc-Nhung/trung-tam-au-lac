<!DOCTYPE html>
<?php
if (isset($_GET['cccd']) && !isset($check)) {
    header('Location: /dang-ky');
    exit();
}
?>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Ký Khóa Học</title>
    <style>
        body {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
            background-image: url('images/bg.png');
            background-position: center;
            background-repeat: no-repeat;
            background-size: 70% auto;
        }

        .register-container {
            max-width: 400px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            padding: 32px 24px 24px 24px;
            backdrop-filter: blur(5px);
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 24px;
            font-size: 26px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
        }

        .form-group input[readonly] {
            background: #f0f0f0;
            color: #000000;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        .input-group input {
            flex: 1;
        }

        .check-btn {
            padding: 10px 15px;
            background: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s;
        }

        .check-btn:hover {
            background: #388E3C;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background: #2196f3;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }

        .register-btn:hover {
            background: #1976d2;
        }

        .home-btn {
            padding: 12px;
            background: #1649e1;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .home-btn:hover {
            background: #616161;
        }

        /* Plain select styling */
        .form-group select:focus {
            border-color: #2196f3;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.2);
        }

    </style>
</head>

<body>
    <div class="register-container">
        <h2>Đăng Ký Khóa Học</h2>
        <form id="registrationForm" onsubmit="handleSubmit(event)">
            <div class="form-group">
                <label for="dau_moi">Chọn Đầu Mối:</label>
                <select id="dau_moi" name="dau_moi" onchange="filterHocVien()">
                    <option value="">-- Chọn đầu mối --</option>
                    @foreach($danh_sach_dau_moi as $dau_moi)
                        <option value="{{ $dau_moi->ma_dau_moi }}" 
                                {{ request('dau_moi') == $dau_moi->ma_dau_moi ? 'selected' : '' }}>
                            {{ $dau_moi->ten_dau_moi }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="hoc_vien">Chọn Học Viên:</label>
                <select id="hoc_vien" name="hoc_vien">
                    <option value="">-- Chọn học viên --</option>
                    @foreach($danh_sach_hoc_vien as $hv)
                        <option value="{{ $hv->cccd }}" {{ isset($check) && $check->cccd == $hv->cccd ? 'selected' : '' }}>
                            {{ $hv->ho }} - [{{ $hv->cccd }}]
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cccd">Số CCCD</label>
                <div class="input-group">
                    <input type="text" value="{{ isset($check) ? $check->cccd : '' }}" id="cccd" name="cccd"
                        placeholder="Nhập số CCCD" required>
                        <input type="hidden" id="cccdReal" name="cccdReal" value="{{ isset($check) ? $check->cccd : '' }}">
                    {{-- <button type="button" class="check-btn" onclick="checkCCCD()">Kiểm tra</button> --}}
                </div>
            </div>
            <div class="form-group">
                <label for="hoten">Họ và Tên:</label>
                <input type="text" value="{{ isset($check) ? $check->ho . ' ' . $check->ten : '' }}" id="hoten" name="hoten"
                    placeholder="Tự nhận theo dữ liệu" readonly>
            </div>
            <div class="form-group">
                <label for="khoahoc">Khóa Học:</label>
                <input type="text" value="{{ isset($check) ? $check->khoa_hoc : '' }}" id="khoahoc" name="khoahoc"
                    placeholder="Tự nhận theo dữ liệu" readonly>
            </div>
            <div class="form-group">
                <label for="ngaysinh">Ngày sinh:</label>
                <input type="text" value="{{ isset($check) ? $check->ngay_sinh : '' }}" id="ngaysinh" name="ngaysinh"
                    placeholder="Tự nhận theo dữ liệu" readonly>
            </div>
            <div class="form-group">
                <label for="giaovien">Giáo Viên:</label>
                <input type="text" readonly value="{{ isset($check) ? $check->dau_moi : '' }}" required id="giaovien" name="daumoi" placeholder="Tự nhận theo dữ liệu">
            </div>
            <div class="form-group">
                <label for="ngayhoc">Ngày thi:</label>
                <input type="text" id="ngayhoc" name="ngayhoc" required readonly value="<?php echo $ngayThi; ?>">
            </div>
            <button type="submit" class="register-btn">Đăng Ký</button>
        </form>
        <a href="/" class="home-btn">Về Trang Chủ</a>
    </div>
    <script>
        // Khởi tạo sự kiện khi DOM đã load
        document.addEventListener('DOMContentLoaded', function() {
            const hocVienSelect = document.getElementById('hoc_vien');
            if (!hocVienSelect) {
                console.error('Không tìm thấy element hoc_vien');
                return;
            }
            
            // Thêm event listener cho select học viên
            hocVienSelect.addEventListener('change', function() {
                console.log('Sự kiện thay đổi học viên được kích hoạt');
                fillHocVienInfo();
            });
        });
        
        function filterHocVien() {
            const dauMoiSelect = document.getElementById('dau_moi');
            const selectedDauMoi = dauMoiSelect.value;
            
            // Xóa thông tin học viên hiện tại khi thay đổi đầu mối
            document.getElementById('hoc_vien').selectedIndex = 0;
            
            clearHocVienInfo();
            
            // Chuyển hướng đến URL mới với tham số dau_moi
            if (selectedDauMoi) {
                window.location.href = '/dang-ky?dau_moi=' + encodeURIComponent(selectedDauMoi);
            } else {
                window.location.href = '/dang-ky';
            }
        }
        
        function fillHocVienInfo() {
            const selectedValue = document.getElementById('hoc_vien').value;
            
            console.log('Học viên được chọn:', selectedValue);
            
            if (!selectedValue) {
                console.log('Không có học viên nào được chọn, xóa thông tin');
                clearHocVienInfo();
                return;
            }
            
            console.log('Đang cập nhật thông tin học viên với CCCD:', selectedValue);
            
            // Cập nhật các trường thông tin học viên
            document.getElementById('cccd').value = selectedValue;
            document.getElementById('cccdReal').value = selectedValue;
            
            // Gọi API để lấy thông tin chi tiết của học viên
            console.log('Đang gọi API để lấy thông tin học viên với CCCD:', selectedValue);
            fetch('/get-hoc-vien-info', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cccd: selectedValue })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Phản hồi từ API học viên:', data);
                if (data.rc == 0 && data.data) {
                    const hocVien = data.data;
                    console.log('Thông tin học viên nhận được:', hocVien);
                    
                    document.getElementById('hoten').value = hocVien.ho_va_ten;
                    document.getElementById('ngaysinh').value = hocVien.ngay_sinh;
                    document.getElementById('khoahoc').value = hocVien.khoa_hoc;
                    document.getElementById('giaovien').value = hocVien.dau_moi;
                    
                    console.log('Đã cập nhật thông tin học viên thành công');
                    console.log('Họ tên:', hocVien.ho_va_ten);
                    console.log('Ngày sinh:', hocVien.ngay_sinh);
                    console.log('Khóa học:', hocVien.khoa_hoc);
                    console.log('Đầu mối:', hocVien.dau_moi);
                } else {
                    console.error('Lỗi khi lấy thông tin học viên:', data.message);
                }
            })
            .catch(error => {
                console.error('Lỗi khi gọi API:', error);
            });
        }
        
        function checkAvailableTimeSlots() {
            const selectedDate = document.getElementById('ngayhoc').value;
            if (!selectedDate) return;

            // Gọi API để lấy thông tin số lượng đăng ký cho từng khung giờ
            fetch('/check-available-slots', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ ngay_hoc: selectedDate })
            })
            .then(response => response.json())
            .then(data => {
                const timeSelect = document.getElementById('giohoc');
                const options = timeSelect.options;
                
                // Reset select về option đầu tiên
                timeSelect.selectedIndex = 0;
                var duLieu = data.data;
                // Cập nhật text cho từng option dựa trên số lượng đăng ký
                for (let i = 0; i < options.length; i++) {
                    const timeValue = options[i].value;
                    const slotInfo = duLieu[i] // Giả sử max là 10 người
                    options[i].text = `${timeValue} - [${slotInfo?.total}/6]`;
                    // Disable option nếu đã đủ người
                    options[i].disabled = slotInfo?.total >= 6;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi kiểm tra khung giờ!');
            });
        }

        function checkCCCD() {
            const cccdInput = document.getElementById('cccd');
            const cccdValue = cccdInput.value.trim();
            
            if (!cccdValue) {
                alert('Vui lòng nhập số CCCD');
                return;
            }
            // Chuyển hướng đến URL mới với tham số cccd
            window.location.href = '/dang-ky?cccd=' + encodeURIComponent(cccdValue);
        }

        function handleSubmit(event) {
            event.preventDefault();
            let hoTen = document.getElementById('hoten').value;
            if(hoTen == ''){
                alert('Vui lòng nhập họ và tên');
                return;
            }
            let ngayHoc = document.getElementById('ngayhoc').value;
            if(ngayHoc == ''){
                alert('Chưa mở lịch thi. Vui lòng quay lại sau');
                return;
            }
            // Lấy tất cả dữ liệu từ form
            const formData = {
                cccd: document.getElementById('cccdReal').value,
            };

            // Log dữ liệu ra console
            console.log('Form Data:', formData);

            // Gửi dữ liệu đến backend
            fetch('/dang-ky-hoc-phan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.rc == 0) {
                    //về trang home
                    window.location.href = '/';
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi đăng ký!');
            });
        }
        
        // Hàm xóa thông tin học viên
        function clearHocVienInfo() {
            console.log('Đang xóa thông tin học viên');
            document.getElementById('cccd').value = '';
            document.getElementById('cccdReal').value = '';
            document.getElementById('hoten').value = '';
            document.getElementById('ngaysinh').value = '';
            document.getElementById('khoahoc').value = '';
            document.getElementById('giaovien').value = '';
            console.log('Đã xóa thông tin học viên thành công');
        }
    </script>
</body>

</html>
