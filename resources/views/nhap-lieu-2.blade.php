<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập liệu Excel</title>
    <!-- SheetJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            width: 100vw;
            box-sizing: border-box;
        }

        .container {
            width: 100vw;
            min-height: 100vh;
            margin: 0;
            background: #fff;
            border-radius: 0;
            box-shadow: none;
            padding: 32px 2vw 24px 2vw;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #2d3a4b;
            margin-bottom: 24px;
        }

        .upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 32px;
            border: 2px dashed #4f8cff;
            border-radius: 10px;
            padding: 36px 0 24px 0;
            background: #f0f6ff;
            transition: border-color 0.2s, background 0.2s;
            position: relative;
        }

        .upload-area.dragover {
            border-color: #2563eb;
            background: #e3edff;
        }

        .custom-file-input {
            display: none;
        }

        .file-label {
            background: #4f8cff;
            color: #fff;
            padding: 12px 32px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }

        .file-label:hover {
            background: #2563eb;
        }

        .file-name {
            margin-top: 12px;
            color: #555;
            font-size: 15px;
        }

        #excelData {
            width: 100%;
            overflow-x: auto;
            max-height: 70vh;
            overflow-y: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border-radius: 8px;
            background: #fafbfc;
            margin-bottom: 16px;
            scrollbar-width: thin;
            scrollbar-color: #4f8cff #e3e6ed;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            background: #fafbfc;
            min-width: 600px;
        }

        th,
        td {
            border: 1px solid #e3e6ed;
            padding: 10px 8px;
            text-align: left;
            max-width: 400px;
            word-break: break-word;
            font-size: 15px;
        }

        th {
            background: #e3e6ed;
            color: #2d3a4b;
        }

        tr:nth-child(even) {
            background: #f4f6fb;
        }

        .no-data {
            text-align: center;
            color: #888;
            margin-top: 32px;
        }

        /* Custom scrollbar for webkit browsers */
        #excelData::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        #excelData::-webkit-scrollbar-thumb {
            background: #4f8cff;
            border-radius: 4px;
        }

        #excelData::-webkit-scrollbar-track {
            background: #e3e6ed;
            border-radius: 4px;
        }

        .data-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 20px;
            color: #2d3a4b;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #4f8cff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #f8fafc;
            border: 1px solid #e3e6ed;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #4f8cff;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 4px;
        }

    </style>
    @if (app()->bound('Illuminate\Foundation\Application'))
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
</head>

<body>
    <div class="container">

        <div class="upload-area" id="uploadArea">
            <label class="file-label" for="excelInput">Chọn file Excel</label>
            <input type="file" id="excelInput" class="custom-file-input" accept=".xlsx,.xls" />
            <div class="file-name" id="fileName"></div>
            <div style="color:#888; font-size:14px; margin-top:10px;">Hoặc kéo thả file vào đây</div>
        </div>
        <div id="excelData" style="padding: 30px 16px;">
            <div class="no-data">Vui lòng chọn file Excel để xem nội dung.</div>
        </div>
        <div class="data-section">
            <h2 class="section-title">Dữ liệu học viên hiện tại</h2>
            <div style="margin-bottom: 16px;">
                <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, CCCD, khóa học..."
                    style="width: 100%; padding: 12px; border: 1px solid #e3e6ed; border-radius: 6px; font-size: 15px;">
            </div>

            <div id="hocVienData">
                @if($hocViens->count() > 0)
                <table id="hocVienTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ</th>
                            <th>Tên</th>
                            <th>Ngày sinh</th>
                            <th>CCCD</th>
                            <th>Địa chỉ</th>
                            <th>Khóa học</th>
                            <th>Nội dung thi</th>
                            <th>Ngày sát hạch</th>
                            <th>Đầu mối</th>
                            <th>Lý thuyết</th>
                            <th>Mô phỏng</th>
                            <th>Thực hành</th>
                            <th>Đường trường</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hocViens as $index => $hocVien)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $hocVien->ho }}</td>
                            <td>{{ $hocVien->ten }}</td>
                            <td>{{ $hocVien->ngay_sinh }}</td>
                            <td>{{ $hocVien->cccd }}</td>
                            <td>{{ $hocVien->dia_chi }}</td>
                            <td>{{ $hocVien->khoa_hoc }}</td>
                            <td>{{ $hocVien->noi_dung_thi }}</td>
                            <td>{{ $hocVien->ngay_sat_hach }}</td>
                            <td>{{ $hocVien->dau_moi }}</td>
                            <td>{{ $hocVien->ly_thuyet }}</td>
                            <td>{{ $hocVien->mo_phong }}</td>
                            <td>{{ $hocVien->thuc_hanh }}</td>
                            <td>{{ $hocVien->duong_truong }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="no-data">Chưa có dữ liệu học viên nào.</div>
                @endif
            </div>
        </div>

        <div style="text-align:center; margin-top:20px;">
            <button id="importBtn"
                style="display:none; background:#4f8cff; color:#fff; border:none; border-radius:6px; padding:12px 32px; font-size:16px; cursor:pointer; transition:background 0.2s; margin-right:10px;">Nhập
                dữ liệu</button>
            <button onclick="location.reload()"
                style="background:#28a745; color:#fff; border:none; border-radius:6px; padding:12px 32px; font-size:16px; cursor:pointer; transition:background 0.2s; margin-right:10px;">Làm
                mới dữ liệu</button>
            <a href="/"
                style="display:inline-block; background:#2d3a4b; color:#fff; border:none; border-radius:6px; padding:12px 32px; font-size:16px; cursor:pointer; transition:background 0.2s; text-decoration:none;">Về
                trang chủ</a>
        </div>
        <div id="jsonOutput"
            style="max-width:100vw; margin:24px auto 0 auto; background:#f8fafc; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.04); padding:18px 12px; font-family:monospace; color:#222; font-size:15px; display:none; white-space:pre-wrap; word-break:break-all;">
        </div>
    </div>
    <script>
        const excelInput = document.getElementById('excelInput');
        const fileName = document.getElementById('fileName');
        const excelData = document.getElementById('excelData');
        const uploadArea = document.getElementById('uploadArea');

        document.querySelector('.file-label').onclick = () => excelInput.click();

        excelInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            fileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function (e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const json = XLSX.utils.sheet_to_json(worksheet, {
                    header: 1
                });
                
                // Console log dữ liệu JSON gốc từ Excel
                console.log('=== DỮ LIỆU EXCEL GỐC (CHỌN FILE) ===');
                console.log('Dữ liệu JSON gốc từ Excel:', json);
                console.log('Số hàng dữ liệu:', json.length);
                console.log('Tên các sheet:', workbook.SheetNames);
                console.log('Sheet đang sử dụng:', sheetName);
                console.log('=====================================');
                
                renderTable(json);
            };
            reader.readAsArrayBuffer(file);
        });

        // Kéo thả file
        uploadArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            if (!file) return;
            if (!file.name.match(/\.(xlsx|xls)$/i)) {
                alert('Vui lòng chọn file Excel (.xlsx, .xls)');
                return;
            }
            fileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function (e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const json = XLSX.utils.sheet_to_json(worksheet, {
                    header: 1
                });
                
                // Console log dữ liệu JSON gốc từ Excel
                console.log('=== DỮ LIỆU EXCEL GỐC (KÉO THẢ) ===');
                console.log('Dữ liệu JSON gốc từ Excel:', json);
                console.log('Số hàng dữ liệu:', json.length);
                console.log('Tên các sheet:', workbook.SheetNames);
                console.log('Sheet đang sử dụng:', sheetName);
                console.log('==================================');
                
                renderTable(json);
            };
            reader.readAsArrayBuffer(file);
        });

        function renderTable(data) {
            console.log('=== DỮ LIỆU ĐẦU VÀO RENDER TABLE ===');
            console.log('Dữ liệu đầu vào:', data);
            console.log('Số hàng đầu vào:', data.length);
            
            if (!data.length) {
                excelData.innerHTML = '<div class="no-data">Không có dữ liệu để hiển thị.</div>';
                document.getElementById('importBtn').style.display = 'none';
                document.getElementById('jsonOutput').style.display = 'none';
                return;
            }

            // Chỉ lấy dữ liệu từ hàng 2 trở đi và chỉ 13 cột đầu tiên
            const processedData = data.slice(1).map(row => {
                return row.slice(0, 13); // Chỉ lấy 13 cột đầu tiên
            }).filter(row => row.some(cell => cell !== undefined && cell !== null && cell !== ''));

            console.log('=== DỮ LIỆU SAU KHI XỬ LÝ ===');
            console.log('Dữ liệu sau khi xử lý:', processedData);
            console.log('Số hàng sau khi xử lý:', processedData.length);
            console.log('================================');

            if (processedData.length === 0) {
                excelData.innerHTML = '<div class="no-data">Không có dữ liệu hợp lệ để hiển thị.</div>';
                document.getElementById('importBtn').style.display = 'none';
                document.getElementById('jsonOutput').style.display = 'none';
                return;
            }

            // Định nghĩa tên cột
            const columnHeaders = [
                'Họ', 'Tên', 'Ngày sinh', 'CCCD', 'Địa chỉ', 
                'Khóa học', 'Nội dung thi', 'Ngày sát hạch', 'Đầu mối', 
                'Lý thuyết', 'Mô phỏng', 'Thực hành', 'Đường trường'
            ];

            let html = '<table>';
            
            // Thêm header
            html += '<tr>';
            columnHeaders.forEach(header => {
                html += `<th>${header}</th>`;
            });
            html += '</tr>';

            // Thêm dữ liệu
            processedData.forEach((row, idx) => {
                html += '<tr>';
                row.forEach((cell, cellIdx) => {
                    html += `<td>${cell ?? ''}</td>`;
                });
                // Nếu row có ít hơn 13 cột, thêm các ô trống
                for (let i = row.length; i < 13; i++) {
                    html += '<td></td>';
                }
                html += '</tr>';
            });
            
            html += '</table>';
            excelData.innerHTML = html;
            document.getElementById('importBtn').style.display = 'inline-block';
            document.getElementById('jsonOutput').style.display = 'none';
            window._excelRawData = processedData;
        }

        // Xử lý nút Nhập dữ liệu
        document.getElementById('importBtn').onclick = async function () {
            const data = window._excelRawData || [];
            if (data.length === 0) {
                document.getElementById('jsonOutput').textContent = 'Không có dữ liệu.';
                document.getElementById('jsonOutput').style.display = 'block';
                return;
            }
            
            // Định nghĩa tên cột để mapping
            const columnHeaders = [
                'ho', 'ten', 'ngay_sinh', 'cccd', 'dia_chi', 
                'khoa_hoc', 'noi_dung_thi', 'ngay_sat_hach', 'dau_moi', 
                'ly_thuyet', 'mo_phong', 'thuc_hanh', 'duong_truong'
            ];
            
            const jsonArr = data.map(row => {
                const obj = {};
                columnHeaders.forEach((header, index) => {
                    obj[header] = row[index] || '';
                });
                return obj;
            });

            console.log('=== DỮ LIỆU JSON ĐỂ GỬI LÊN SERVER ===');
            console.log('Dữ liệu JSON:', jsonArr);
            console.log('Số bản ghi:', jsonArr.length);
            console.log('========================================');

            if (confirm('Bạn có chắc chắn muốn gửi dữ liệu này lên hệ thống?')) {
                try {
                    const response = await fetch('/nhap-lieu-json', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            data: jsonArr
                        })
                    });
                    if (response.ok) {
                        alert('Gửi dữ liệu thành công!');
                    } else {
                        const err = await response.json().catch(() => null);
                        alert('Gửi dữ liệu thất bại! ' + (err?.message || ''));
                    }
                } catch (e) {
                    alert('Có lỗi khi gửi dữ liệu: ' + e.message);
                }
            }
        }

        // Tìm kiếm trong bảng học viên
        document.getElementById('searchInput').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('hocVienTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        });

    </script>
</body>

</html>
