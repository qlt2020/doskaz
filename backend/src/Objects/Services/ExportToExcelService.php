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
        $total_sum = 0;

        foreach ($data as $main_category_name=>$main_category) {
            $sheet->setCellValueByColumnAndRow($col++, $row, $id);
            $sheet->setCellValueByColumnAndRow($col, $row, $main_category_name);
            $cat_col = $col+1;
            $cat_row = $row;
            $sheet->getStyle($row)->getFont()->setBold(true);
            $sub_id = 1;
            $row++;
            $main_cat_sum_total = 0;
            $main_cat_sum_full = 0;
            $main_cat_sum_partial = 0;
            $main_cat_sum_no = 0;
            $old_category_name = '';

            foreach ($main_category as $category_name=>$category) {
                $sheet->setCellValueByColumnAndRow(1, $row, $id . ". " . $sub_id);
                $sheet->setCellValueByColumnAndRow($col++, $row, $category_name);
                $cat_col=3;
                $total_col = 3;

                foreach ($category as $group_name=>$group) {
                    if ($start) {
                        $this->excelHeader($sheet, $col, $headerRow, $this->names[$group_name]);
                    }
                    $sheet->setCellValueByColumnAndRow($col++, $row, $group['total_access']);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $group['full_access']);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $group['partial_access']);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $group['no_access']);

                    if ($category_name != $old_category_name) {
                        $main_cat_sum_total = $sheet->getCellByColumnAndRow($cat_col, $cat_row)->getValue() ?? 0;
                        $main_cat_sum_full = $sheet->getCellByColumnAndRow($cat_col+1, $cat_row)->getValue() ?? 0;
                        $main_cat_sum_partial = $sheet->getCellByColumnAndRow($cat_col+2, $cat_row)->getValue() ?? 0;
                        $main_cat_sum_no = $sheet->getCellByColumnAndRow($cat_col+3, $cat_row)->getValue() ?? 0;
                        $main_cat_sum_total += $group['total_access'];
                        $main_cat_sum_full += $group['full_access'];
                        $main_cat_sum_partial += $group['partial_access'];
                        $main_cat_sum_no += $group['no_access'];
                        $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $main_cat_sum_total);
                        $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $main_cat_sum_full);
                        $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $main_cat_sum_partial);
                        $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $main_cat_sum_no);

                        $total_sum = $sheet->getCellByColumnAndRow($total_col, 5)->getValue() ?? 0;
                        $total_full = $sheet->getCellByColumnAndRow($total_col+1, 5)->getValue() ?? 0;
                        $total_partial = $sheet->getCellByColumnAndRow($total_col+2, 5)->getValue() ?? 0;
                        $total_no = $sheet->getCellByColumnAndRow($total_col+3, 5)->getValue() ?? 0;
                        $total_sum += $group['total_access'];
                        $total_full += $group['full_access'];
                        $total_partial += $group['partial_access'];
                        $total_no += $group['no_access'];
                        $sheet->setCellValueByColumnAndRow($total_col++, 5, $total_sum);
                        $sheet->setCellValueByColumnAndRow($total_col++, 5, $total_full);
                        $sheet->setCellValueByColumnAndRow($total_col++, 5, $total_partial);
                        $sheet->setCellValueByColumnAndRow($total_col++, 5, $total_no);   
                    }
                }
                $old_category_name = $category_name;
                $start = false;
                $row++;
                $sub_id++;
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