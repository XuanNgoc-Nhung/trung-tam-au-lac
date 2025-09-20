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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HocPhiTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithDefaultStyles
{
    public function array(): array
    {
        return [
            [
                '001', 'Nguyễn Văn A', '01/01/2000', '1234567890123', '123 Đường ABC Quận 1 TP.HCM', 
                'B2', 'Đầu mối 1', '5000000', 'Đã thanh toán'
            ],
            [
                '002', 'Trần Thị B', '02/02/2001', '9876543210987', '456 Đường XYZ Quận 2 TP.HCM', 
                'B1', 'Đầu mối 2', '6000000', 'Đã thanh toán'
            ],
            [
                '003', 'Lê Văn C', '15/03/1995', '1112223334445', '789 Đường DEF Quận 3 TP.HCM', 
                'A1', 'Đầu mối 3', '7500000', 'Chưa thanh toán'
            ],
            [
                '004', 'Phạm Thị D', '20/04/1998', '5556667778889', '321 Đường GHI Quận 4 TP.HCM', 
                'B2', 'Đầu mối 1', '5500000', 'Đã thanh toán'
            ],
            [
                '005', 'Hoàng Văn E', '10/05/1997', '9998887776665', '654 Đường JKL Quận 5 TP.HCM', 
                'A2', 'Đầu mối 2', '8000000', 'Chưa thanh toán'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Số báo danh', 'Họ và tên', 'Ngày sinh', 'CCCD', 'Địa chỉ', 
            'Hạng', 'Đầu mối', 'Lệ phí', 'Trạng thái thanh toán'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Số báo danh
            'B' => 25,  // Họ và tên
            'C' => 15,  // Ngày sinh
            'D' => 20,  // CCCD
            'E' => 40,  // Địa chỉ
            'F' => 10,  // Hạng
            'G' => 20,  // Đầu mối
            'H' => 15,  // Lệ phí
            'I' => 20,  // Trạng thái thanh toán
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
            'A2:I6' => [
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

    public function defaultStyles(DefaultStyles $defaultStyle)
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
