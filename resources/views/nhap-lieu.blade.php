<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập liệu Excel</title>
    <!-- SheetJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <style>
        html, body {
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
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
        th, td {
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
    </style>
    @if (app()->bound('Illuminate\Foundation\Application'))
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
</head>
<body>
    <div class="container">
        <h1>Nhập liệu từ file Excel</h1>
        <div class="upload-area" id="uploadArea">
            <label class="file-label" for="excelInput">Chọn file Excel</label>
            <input type="file" id="excelInput" class="custom-file-input" accept=".xlsx,.xls" />
            <div class="file-name" id="fileName"></div>
            <div style="color:#888; font-size:14px; margin-top:10px;">Hoặc kéo thả file vào đây</div>
        </div>
        <div id="excelData">
            <div class="no-data">Vui lòng chọn file Excel để xem nội dung.</div>
        </div>
        <div style="text-align:center; margin-top:20px;">
            <button id="importBtn" style="display:none; background:#4f8cff; color:#fff; border:none; border-radius:6px; padding:12px 32px; font-size:16px; cursor:pointer; transition:background 0.2s; margin-right:10px;">Nhập dữ liệu</button>
            <a href="/" style="display:inline-block; background:#2d3a4b; color:#fff; border:none; border-radius:6px; padding:12px 32px; font-size:16px; cursor:pointer; transition:background 0.2s; text-decoration:none;">Về trang chủ</a>
        </div>
        <div id="jsonOutput" style="max-width:100vw; margin:24px auto 0 auto; background:#f8fafc; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.04); padding:18px 12px; font-family:monospace; color:#222; font-size:15px; display:none; white-space:pre-wrap; word-break:break-all;"></div>
    </div>
    <script>
        const excelInput = document.getElementById('excelInput');
        const fileName = document.getElementById('fileName');
        const excelData = document.getElementById('excelData');
        const uploadArea = document.getElementById('uploadArea');

        document.querySelector('.file-label').onclick = () => excelInput.click();

        excelInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            fileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const json = XLSX.utils.sheet_to_json(worksheet, {header: 1});
                renderTable(json);
            };
            reader.readAsArrayBuffer(file);
        });

        // Kéo thả file
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', function(e) {
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
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const json = XLSX.utils.sheet_to_json(worksheet, {header: 1});
                renderTable(json);
            };
            reader.readAsArrayBuffer(file);
        });

        function renderTable(data) {
            if (!data.length) {
                excelData.innerHTML = '<div class="no-data">Không có dữ liệu để hiển thị.</div>';
                document.getElementById('importBtn').style.display = 'none';
                document.getElementById('jsonOutput').style.display = 'none';
                return;
            }
            let html = '<table>';
            data.forEach((row, idx) => {
                html += '<tr>';
                row.forEach(cell => {
                    html += idx === 0 ? `<th>${cell ?? ''}</th>` : `<td>${cell ?? ''}</td>`;
                });
                html += '</tr>';
            });
            html += '</table>';
            excelData.innerHTML = html;
            document.getElementById('importBtn').style.display = 'inline-block';
            document.getElementById('jsonOutput').style.display = 'none';
            window._excelRawData = data;
        }

        // Xử lý nút Nhập dữ liệu
        document.getElementById('importBtn').onclick = async function() {
            const data = window._excelRawData || [];
            if (data.length < 2) {
                document.getElementById('jsonOutput').textContent = 'Không có dữ liệu.';
                document.getElementById('jsonOutput').style.display = 'block';
                return;
            }
            const rows = data.slice(1).filter(row => row.some(cell => cell !== undefined && cell !== null && cell !== ''));
            const jsonArr = rows.map(row => {
                const obj = {};
                for (let idx = 1; idx <= 7; idx++) { // cột 2 đến 7
                    obj[(idx+1).toString()] = row[idx] ?? '';
                }
                return obj;
            });

            if (confirm('Bạn có chắc chắn muốn gửi dữ liệu này lên hệ thống?')) {
                try {
                    const response = await fetch('/nhap-lieu-json', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({data: jsonArr})
                    });
                    if (response.ok) {
                        alert('Gửi dữ liệu thành công!');
                    } else {
                        const err = await response.json().catch(()=>null);
                        alert('Gửi dữ liệu thất bại! ' + (err?.message || ''));
                    }
                } catch (e) {
                    alert('Có lỗi khi gửi dữ liệu: ' + e.message);
                }
            }
        }
    </script>
</body>
</html>
