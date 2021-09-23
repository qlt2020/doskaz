<?php

namespace App\Objects\Services;
use App\Users\User;
use App\Infrastructure\Exports\BaseExportToExcelService;

class ExportToExcelService extends BaseExportToExcelService {

    protected array $names;

    public function __construct() {
        parent::__construct();
        $this->names = User::USER_CATEGORIES_NAMES;
    }

    public function fillData(array $data) {
        $sheet = $this->spreadsheet->getActiveSheet();
        
        $sheet->getStyle('A2:BF5')->getFont()->setBold(true);
        $sheet->setCellValueByColumnAndRow(2, 2, 'Дата: ' . $this->date);
        $sheet->setCellValueByColumnAndRow(1, 3, '#');
        $sheet->setCellValueByColumnAndRow(2, 3, 'Объект');
        $sheet->setCellValueByColumnAndRow(2, 5, 'Общее');
        $sheet->mergeCellsByColumnAndRow(1, 3, 1, 4);
        $sheet->mergeCellsByColumnAndRow(2, 3, 2, 4);
        $sheet->getColumnDimension('B')->setWidth(45);
        $row = 6;
        $headerRow = 3;
        $col = 1;
        $id = 1;
        $start = true;

        foreach ($data as $mainCategoryName=>$mainCategory) {
            $sheet->setCellValueByColumnAndRow($col++, $row, $id);
            $sheet->setCellValueByColumnAndRow($col, $row, $mainCategoryName);
            $catCol = $col+1;
            $catRow = $row;
            $sheet->getStyle($row)->getFont()->setBold(true);
            $subId = 1;
            $row++;
            $previousCategoryName = '';

            foreach ($mainCategory as $categoryName=>$category) {
                $sheet->setCellValueByColumnAndRow(1, $row, $id . ". " . $subId);
                $sheet->setCellValueByColumnAndRow($col++, $row, $categoryName);
                $catCol=3;
                $totalCol = 3;

                foreach ($category as $groupName=>$group) {
                    if ($start) {
                        $this->excelHeader($sheet, $col, $headerRow, $this->names[$groupName]);
                    }
                    $sheet->setCellValueByColumnAndRow($col++, $row, $group['total_access']);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $group['full_access']);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $group['partial_access']);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $group['no_access']);

                    if ($categoryName != $previousCategoryName) {
                        $mainCatTotalSum = $sheet->getCellByColumnAndRow($catCol, $catRow)->getValue() ?? 0;
                        $mainCatFullSum = $sheet->getCellByColumnAndRow($catCol+1, $catRow)->getValue() ?? 0;
                        $mainCatPartialSum = $sheet->getCellByColumnAndRow($catCol+2, $catRow)->getValue() ?? 0;
                        $mainCatNotSum = $sheet->getCellByColumnAndRow($catCol+3, $catRow)->getValue() ?? 0;
                        $mainCatTotalSum += $group['total_access'];
                        $mainCatFullSum += $group['full_access'];
                        $mainCatPartialSum += $group['partial_access'];
                        $mainCatNotSum += $group['no_access'];
                        $sheet->setCellValueByColumnAndRow($catCol++, $catRow, $mainCatTotalSum);
                        $sheet->setCellValueByColumnAndRow($catCol++, $catRow, $mainCatFullSum);
                        $sheet->setCellValueByColumnAndRow($catCol++, $catRow, $mainCatPartialSum);
                        $sheet->setCellValueByColumnAndRow($catCol++, $catRow, $mainCatNotSum);

                        $totalSum = $sheet->getCellByColumnAndRow($totalCol, 5)->getValue() ?? 0;
                        $totalFullSum = $sheet->getCellByColumnAndRow($totalCol+1, 5)->getValue() ?? 0;
                        $totalPartialSum = $sheet->getCellByColumnAndRow($totalCol+2, 5)->getValue() ?? 0;
                        $totalNotSum = $sheet->getCellByColumnAndRow($totalCol+3, 5)->getValue() ?? 0;
                        $totalSum += $group['total_access'];
                        $totalFullSum += $group['full_access'];
                        $totalPartialSum += $group['partial_access'];
                        $totalNotSum += $group['no_access'];
                        $sheet->setCellValueByColumnAndRow($totalCol++, 5, $totalSum);
                        $sheet->setCellValueByColumnAndRow($totalCol++, 5, $totalFullSum);
                        $sheet->setCellValueByColumnAndRow($totalCol++, 5, $totalPartialSum);
                        $sheet->setCellValueByColumnAndRow($totalCol++, 5, $totalNotSum);   
                    }
                }
                $previousCategoryName = $categoryName;
                $start = false;
                $row++;
                $subId++;
                $col = 2;
            }
            $col = 1;
            $id++;
        }
    }

    private function excelHeader($sheet, $col, $headerRow, $name) {
        $sheet->setCellValueByColumnAndRow($col, $headerRow, $name);
        $sheet->mergeCellsByColumnAndRow($col, $headerRow, $col + 3, $headerRow);
        $sheet->setCellValueByColumnAndRow($col, $headerRow + 1, 'Общее');
        $sheet->setCellValueByColumnAndRow($col + 1, $headerRow + 1, 'Доступно');
        $sheet->setCellValueByColumnAndRow($col + 2, $headerRow + 1, 'Частично доступно');
        $sheet->setCellValueByColumnAndRow($col + 3, $headerRow + 1, 'Недоступно');
    }
}