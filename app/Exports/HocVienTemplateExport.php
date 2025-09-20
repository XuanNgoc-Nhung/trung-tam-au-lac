<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HocVienTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithDefaultStyles
{
    public function array(): array
    {
        return [
            [
                'Nguyễn', 'Văn A', '01/01/2000', "'120123", '123 Đường ABC, Quận 1, TP.HCM', 
                'Khóa 1', 'Bài thi 1', 'Tháng 1/2023', 'Đầu mối 1', 'Ghi chú mẫu 1', '50', '50', '50', '50'
            ],
            [
                'Trần', 'Thị B', '02/02/2001', "'98987", '456 Đường XYZ, Quận 2, TP.HCM', 
                'Khóa 2', 'Bài thi 2', 'Tháng 2/2023', 'Đầu mối 2', 'Ghi chú mẫu 2', '60', '60', '60', '60'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Họ', 'Tên', 'Ngày sinh', 'CCCD', 'Địa chỉ', 
            'Khóa học', 'Nội dung thi', 'Ngày sát hạch', 'Đầu mối', 'Ghi chú', 
            'Lý thuyết', 'Mô phỏng', 'Thực hành', 'Đường trường'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Họ
            'B' => 15,  // Tên
            'C' => 15,  // Ngày sinh
            'D' => 20,  // CCCD
            'E' => 40,  // Địa chỉ
            'F' => 15,  // Khóa học
            'G' => 20,  // Nội dung thi
            'H' => 15,  // Ngày sát hạch
            'I' => 20,  // Đầu mối
            'J' => 30,  // Ghi chú
            'K' => 12,  // Lý thuyết
            'L' => 12,  // Mô phỏng
            'M' => 12,  // Thực hành
            'N' => 15,  // Đường trường
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style cho header (hàng 1)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            // Style cho dữ liệu mẫu
            'A2:N3' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'font' => [
                'name' => 'Arial',
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
    }
}
