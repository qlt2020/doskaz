<?php

namespace App\Users\Services;

use App\Infrastructure\Exports\BaseExportToExcelService;
use App\Users\User;

class ExportToExcelService extends BaseExportToExcelService {

    protected array $names;

    public function __construct() {
        parent::__construct();
        $this->names = User::USER_CATEGORIES_NAMES;
    }

    public function fillData(array $data) {
        $sheet = $this->spreadsheet->getActiveSheet();

        $sheet->getStyle('A2:BF4')->getFont()->setBold(true);
        $sheet->setCellValueByColumnAndRow(2, 2, 'Дата: ' . $this->date);
        $sheet->setCellValueByColumnAndRow(1, 3, '#');
        $sheet->setCellValueByColumnAndRow(2, 3, 'Город');
        $sheet->mergeCellsByColumnAndRow(1, 3, 1, 4);
        $sheet->mergeCellsByColumnAndRow(2, 3, 2, 4);

        $row = 5;
        $headerRow = 3;
        $col = 1;
        $start = true;
        $id = 1;

        foreach ($data as $city) {
            $sheet->setCellValueByColumnAndRow($col++, $row, $id++);
            $sheet->setCellValueByColumnAndRow($col++, $row, $city['name']);

            if ($start) {
                $this->excelHeader($sheet, $col, $headerRow, 'Все');
            }

            $sheet->setCellValueByColumnAndRow($col++, $row, $city['total']['total']);
            $sheet->setCellValueByColumnAndRow($col++, $row, $city['total']['men']);
            $sheet->setCellValueByColumnAndRow($col++, $row, $city['total']['women']);
            $sheet->setCellValueByColumnAndRow($col++, $row, $city['total']['unknown']);

            foreach ($city['categories'] as $cat) {
                if ($start) {
                    $this->excelHeader($sheet, $col, $headerRow, $this->names[$cat['name']]);
                }

                $sheet->setCellValueByColumnAndRow($col++, $row, $cat['total']);
                $sheet->setCellValueByColumnAndRow($col++, $row, $cat['men']);
                $sheet->setCellValueByColumnAndRow($col++, $row, $cat['women']);
                $sheet->setCellValueByColumnAndRow($col++, $row, $cat['unknown']);
            }

            $start = false;
            $row++;
            $col = 1;
        }
    }

    private function excelHeader($sheet, $col, $headerRow, $name) {
        $sheet->setCellValueByColumnAndRow($col, $headerRow, $name);
        $sheet->mergeCellsByColumnAndRow($col, $headerRow, $col + 3, $headerRow);
        $sheet->setCellValueByColumnAndRow($col, $headerRow + 1, 'Общее');
        $sheet->setCellValueByColumnAndRow($col + 1, $headerRow + 1, 'Мужчины');
        $sheet->setCellValueByColumnAndRow($col + 2, $headerRow + 1, 'Женщины');
        $sheet->setCellValueByColumnAndRow($col + 3, $headerRow + 1, 'Не определено');
    }
}
