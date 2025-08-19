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
    <title>Lệ phí sát hạch</title>
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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .modal-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .modal-message {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 0 5px;
            transition: background 0.2s;
        }

        .modal-btn-primary {
            background: #2196f3;
            color: white;
        }

        .modal-btn-primary:hover {
            background: #1976d2;
        }

        .modal-btn-secondary {
            background: #f44336;
            color: white;
        }

        .modal-btn-secondary:hover {
            background: #d32f2f;
        }

        .modal-btn-success {
            background: #4CAF50;
            color: white;
        }

        .modal-btn-success:hover {
            background: #388E3C;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 10px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Kiểm tra tình trạng thanh toán</h2>
        <form id="registrationForm" onsubmit="handleSubmit(event)">
            <div class="form-group">
                <label for="cccd">Số Báo danh {{$cauHinh}}</label>
                <div class="input-group">
                    <input type="text" id="ngayThi" placeholder="Ngày thi" name="ngayThi" value="{{ isset($cauHinh) ? $cauHinh->ngay_thi : '' }}" readonly>
                    <input type="text" value="{{ isset($check) ? $check->sbd : '' }}" id="sbd" name="sbd"
                        placeholder="SBD" required>
                        <input type="hidden" id="cccdReal" name="cccdReal" value="{{ isset($check) ? $check->id  : '' }}">
                    <button type="button" class="check-btn" onclick="checkCCCD()">Check</button>
                </div>
            </div>
            <div class="form-group">
                <label for="hoten">Họ và Tên:</label>
                <input type="text" value="{{ isset($check) ? $check->ho_va_ten : '' }}" id="hoten" name="hoten"
                    placeholder="Tự nhận theo dữ liệu" readonly>
            </div>
            <div class="form-group">
                <label for="hoten">CCCD:</label>
                <input type="text" value="{{ isset($check) ? $check->so_cccd : '' }}" id="cccd" name="cccd"
                    placeholder="Tự nhận theo dữ liệu" readonly>
            </div>
            <div class="form-group">
                <label for="khoahoc">Khóa Học:</label>
                <input type="text" value="{{ isset($check) ? $check->hang : '' }}" id="khoahoc" name="khoahoc"
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
                <label for="ngayhoc">Học phí:</label>
                <input type="text" id="ngayhoc" name="ngayhoc" required readonly value="<?php echo number_format($check->le_phi ?? 0, 0, ',', '.') ?? ''; ?> vnđ">
            </div>
            <button type="submit" class="register-btn">Kiểm tra</button>
        </form>
        <a href="/" class="home-btn">Về Trang Chủ</a>
    </div>

    <!-- Modal thông báo -->
    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-icon" id="modalIcon">⚠️</div>
            <div class="modal-title" id="modalTitle">Thông báo</div>
            <div class="modal-message" id="modalMessage">Nội dung thông báo</div>
            <div id="modalButtons">
                <button class="modal-btn modal-btn-primary" onclick="closeModal()">Đóng</button>
            </div>
        </div>
    </div>
    <script>
        // Hàm hiển thị modal
        function showModal(title, message, icon, buttons) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('modalIcon').textContent = icon;
            
            const modalButtons = document.getElementById('modalButtons');
            modalButtons.innerHTML = '';
            
            if (buttons && buttons.length > 0) {
                buttons.forEach(button => {
                    const btn = document.createElement('button');
                    btn.className = `modal-btn ${button.class}`;
                    btn.textContent = button.text;
                    btn.onclick = button.onclick;
                    modalButtons.appendChild(btn);
                });
            } else {
                const closeBtn = document.createElement('button');
                closeBtn.className = 'modal-btn modal-btn-primary';
                closeBtn.textContent = 'Đóng';
                closeBtn.onclick = closeModal;
                modalButtons.appendChild(closeBtn);
            }
            
            document.getElementById('notificationModal').style.display = 'block';
        }

        // Hàm đóng modal
        function closeModal() {
            document.getElementById('notificationModal').style.display = 'none';
        }

        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const modal = document.getElementById('notificationModal');
            if (event.target == modal) {
                closeModal();
            }
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
            const cccdInput = document.getElementById('sbd');
            const cccdValue = cccdInput.value.trim();
            const ngayThi = document.getElementById('ngayThi').value.trim();
           
            if(!ngayThi){
                alert('Vui lòng nhập ngày thi');
                return;
            } 
            if (!cccdValue) {
                alert('Vui lòng nhập số báo danh');
                return;
            }
            // Chuyển hướng đến URL mới với tham số cccd
            window.location.href = '/le-phi?sbd=' + encodeURIComponent(cccdValue)+'&ngay_thi='+encodeURIComponent(ngayThi);
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
                id: document.getElementById('cccdReal').value,
            };

            // Log dữ liệu ra console
            console.log('Form Data:', formData);

            // Gửi dữ liệu đến backend
            fetch('/check-thanh-toan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                
                // Xử lý response theo rc
                if (data.rc === -1) {
                    // Không tìm thấy học viên
                    showModal(
                        'Không tìm thấy học viên',
                        'Số CCCD không tồn tại trong hệ thống. Vui lòng kiểm tra lại thông tin.',
                        '❌',
                        [
                            {
                                text: 'Đóng',
                                class: 'modal-btn-primary',
                                onclick: closeModal
                            }
                        ]
                    );
                } else if (data.rc === 0) {
                    // Đã thanh toán
                    showModal(
                        'Đã thanh toán',
                        'Học viên đã thanh toán học phí.',
                        '✅',
                        [
                            {
                                text: 'Đóng',
                                class: 'modal-btn-success',
                                onclick: closeModal
                            }
                        ]
                    );
                } else if (data.rc === 1) {
                    // Chưa thanh toán
                    showModal(
                        'Chưa thanh toán',
                        'Học viên chưa thanh toán học phí. Vui lòng thanh toán trước khi thi.',
                        '⚠️',
                        [
                            {
                                text: 'Thanh toán ngay',
                                class: 'modal-btn-primary',
                                onclick: () => {
                                    closeModal();
                                    window.location.href = '/thanh-toan?lpt=' + encodeURIComponent(document.getElementById('cccdReal').value);
                                }
                            },
                            {
                                text: 'Đóng',
                                class: 'modal-btn-secondary',
                                onclick: closeModal
                            }
                        ]
                    );
                } else {
                    // Trường hợp khác
                    showModal(
                        'Lỗi hệ thống',
                        'Có lỗi xảy ra khi kiểm tra tình trạng thanh toán. Vui lòng thử lại sau.',
                        '❌',
                        [
                            {
                                text: 'Đóng',
                                class: 'modal-btn-secondary',
                                onclick: closeModal
                            }
                        ]
                    );
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
