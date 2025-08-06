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

    </style>
</head>

<body>
    <div class="register-container">
        <h2>Đăng Ký Khóa Học</h2>
        <form id="registrationForm" onsubmit="handleSubmit(event)">
            <div class="form-group">
                <label for="cccd">Số CCCD</label>
                <div class="input-group">
                    <input type="text" value="{{ isset($check) ? $check->cccd : '' }}" id="cccd" name="cccd"
                        placeholder="Nhập số CCCD" required>
                        <input type="hidden" id="cccdReal" name="cccdReal" value="{{ isset($check) ? $check->cccd : '' }}">
                    <button type="button" class="check-btn" onclick="checkCCCD()">Kiểm tra</button>
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
                <input type="text" readonly value="{{ isset($check) ? $check->dau_moi : '' }}" required id="" name="daumoi" placeholder="Tự nhận theo dữ liệu">
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
                console.log(data);

                const timeSelect = document.getElementById('giohoc');
                const options = timeSelect.options;
                
                // Reset select về option đầu tiên
                timeSelect.selectedIndex = 0;
                var duLieu = data.data;
                // Cập nhật text cho từng option dựa trên số lượng đăng ký
                for (let i = 0; i < options.length; i++) {
                    console.log(options[i].value);
                    const timeValue = options[i].value;
                    const slotInfo = duLieu[i] // Giả sử max là 10 người
                    console.log(slotInfo);
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
                    window.location.href = '/thanh-toan?cccd=' + encodeURIComponent(document.getElementById('cccdReal').value);
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi đăng ký!');
            });
        }
    </script>
</body>

</html>
