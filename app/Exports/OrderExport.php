<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Order;


class OrderExport  implements FromCollection,WithColumnWidths, WithStyles,WithHeadings
{
    protected $collections;

    function __construct($collections)
    {
        $this->collections = $collections;
    }

    public function collection()
    {
        return collect($this->collections['data']);
    }

    /*設定每一行的寬度*/
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 60,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 60,
            'I' => 20,
            'J' => 15
        ];
    }

    /*設定標題列*/
    public function headings(): array
    {
        //第一列為先放一個空白的資料，後面會取代掉
        return [
            ["匯出日期：".date("Y-m-d H:i:s")],
            ['Ig','姓名', '地址', '電話', '設計費', '加購商品金額', '總價', '訂購商品內容','建立時間','訂單狀態']
        ];
    }

    /*資料表的各種樣式設定*/
    public function styles(Worksheet $sheet)
    {
        $rows = count($this->collections['data']) + 2;

        //設定所有列的共同高度
        $sheet->getDefaultRowDimension()->setRowHeight(36);

        //合併第一列
        $sheet->mergeCells("A1:H1");

        //在第一格中寫入測驗的相關資料
        $sheet->setCellValue("A1",
                             "匯出日期：".date("Y-m-d H:i:s"));

        //設定相關欄位的樣式
        return [
            "A1:J$rows" => [
                'font' => [
                    'size' => 12,
                    'name' => '標楷體',
                ],
                'alignment' => [
                    'vertical' => 'center',
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            "A3:C$rows"=>[
                'alignment' => [
                    'horizontal' => 'center',
                ],
            ],
            "A2:J2" => [
                'fill'=>[
                    'fillType'=>Fill::FILL_SOLID,
                    'startColor'=>['argb'=>'000000'],
                ],
                'font'=>[
                    'color'=>['argb'=>'ffffff']
                ],
                'alignment' => [
                    'horizontal' => 'center',
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['argb' => '999999'],
                    ],
                ],
            ],
            "A3:A$rows" => [
                'fill'=>[
                    'fillType'=>Fill::FILL_SOLID,
                    'startColor'=>['argb'=>'000000'],
                ],
                'font'=>[
                    'color'=>['argb'=>'ffffff']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['argb' => '999999'],
                    ],
                ],
            ],
            "B3:B$rows" => [
                'fill'=>[
                    'fillType'=>Fill::FILL_SOLID,
                    'startColor'=>['argb'=>'00EE00'],
                ],
                'font'=>[
                    'color'=>['argb'=>'000000']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}

