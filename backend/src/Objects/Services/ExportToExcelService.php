<?php

namespace App\Objects\Services;

use App\Infrastructure\Exports\BaseExportToExcelService;

class ExportToExcelService extends BaseExportToExcelService {

    public function fillData(array $data) {
        $sheet = $this->spreadsheet->getActiveSheet();

        $sheet->getStyle('A2:BF5')->getFont()->setBold(true);
        $sheet->setCellValueByColumnAndRow(2, 2, 'Дата: ' . $this->date);
        $sheet->setCellValueByColumnAndRow(1, 3, '#');
        $sheet->setCellValueByColumnAndRow(2, 3, 'Объект');
        $sheet->setCellValueByColumnAndRow(2, 5, 'Общее');
        $sheet->setCellValueByColumnAndRow(3, 3, 'Общее по статусам');
        $sheet->mergeCellsByColumnAndRow(1, 3, 1, 4);
        $sheet->mergeCellsByColumnAndRow(2, 3, 2, 4);
        $sheet->mergeCellsByColumnAndRow(3, 3, 3, 4);
        $sheet->getColumnDimension('B')->setWidth(45);

        $row = 6;
        $headerRow = 3;
        $col_head = 7;
        $col = 1;
        $id = 1;
        $total_sum = 0;
        $full_acess_total_sum = 0;
        $part_acess_total_sum = 0;
        $no_access_total_sum = 0;
        $mfc_total_sum = 0;
        $mpc_total_sum = 0;
        $mnc_total_sum = 0;
        $vfc_total_sum = 0;
        $vpc_total_sum = 0;
        $vnc_total_sum = 0;
        $lfc_total_sum = 0;
        $lpc_total_sum = 0;
        $lnc_total_sum = 0;
        $hfc_total_sum = 0;
        $hpc_total_sum = 0;
        $hnc_total_sum = 0;
        $ifc_total_sum = 0;
        $ipc_total_sum = 0;
        $inc_total_sum = 0;
        $kfc_total_sum = 0;
        $kpc_total_sum = 0;
        $knc_total_sum = 0;

        $this->excelHeader($sheet, 4, $headerRow, 'Для всех групп');
        $names = array('Люди, передвигающиеся на кресло-коляске', 'Люди с детскими колясками', 'Люди с с инвалидностью по зрению',
            'Люди с нарушениями опорно-двигательного аппарата', 'Временно травмированные люди', 'Люди с отсутствующими конечностями', 'Беременные женщины', 'Пожилые люди',
            'Люди с инвалидностью по слуху', 'Люди с интеллектуальной инвалидностью', 'Люди с детьми до семи лет');
        foreach ($names as $name) {
            $this->excelHeader($sheet, $col_head, $headerRow, $name);
            $col_head = $col_head + 3;
        }

        foreach ($data as $key=>$main_category) {
            $sheet->setCellValueByColumnAndRow($col++, $row, $id);
            $sheet->setCellValueByColumnAndRow($col, $row, $key);
            $cat_col = $col+1;
            $cat_row = $row;
            $sheet->getStyle($row)->getFont()->setBold(true);

            $sub_id = 1;
            $row++;
            $cat_total_sum = 0;
            $cat_full_acess_total_sum = 0;
            $cat_part_acess_total_sum = 0;
            $cat_no_access_total_sum = 0;
            $cat_mfc_total_sum = 0;
            $cat_mpc_total_sum = 0;
            $cat_mnc_total_sum = 0;
            $cat_vfc_total_sum = 0;
            $cat_vpc_total_sum = 0;
            $cat_vnc_total_sum = 0;
            $cat_lfc_total_sum = 0;
            $cat_lpc_total_sum = 0;
            $cat_lnc_total_sum = 0;
            $cat_hfc_total_sum = 0;
            $cat_hpc_total_sum = 0;
            $cat_hnc_total_sum = 0;
            $cat_ifc_total_sum = 0;
            $cat_ipc_total_sum = 0;
            $cat_inc_total_sum = 0;
            $cat_kfc_total_sum = 0;
            $cat_kpc_total_sum = 0;
            $cat_knc_total_sum = 0;

            foreach ($main_category as $name=>$category) {
                $sheet->setCellValueByColumnAndRow(1, $row, $id . ". " . $sub_id);
                $sheet->setCellValueByColumnAndRow($col++, $row, $name);

                $col = $col + 4;

                $subcat_mfc_total_sum = 0;
                $subcat_mpc_total_sum = 0;
                $subcat_mnc_total_sum = 0;
                $subcat_vfc_total_sum = 0;
                $subcat_vpc_total_sum = 0;
                $subcat_vnc_total_sum = 0;
                $subcat_lfc_total_sum = 0;
                $subcat_lpc_total_sum = 0;
                $subcat_lnc_total_sum = 0;
                $subcat_hfc_total_sum = 0;
                $subcat_hpc_total_sum = 0;
                $subcat_hnc_total_sum = 0;
                $subcat_ifc_total_sum = 0;
                $subcat_ipc_total_sum = 0;
                $subcat_inc_total_sum = 0;
                $subcat_kfc_total_sum = 0;
                $subcat_kpc_total_sum = 0;
                $subcat_knc_total_sum = 0;

                foreach($category as $category_part) {
                    $subcat_mfc_total_sum += $category_part['movement_full_accessible'];
                    $subcat_mpc_total_sum += $category_part['movement_partial_accessible'];
                    $subcat_mnc_total_sum += $category_part['movement_not_accessible'];
                    $subcat_vfc_total_sum += $category_part['vision_full_accessible'];
                    $subcat_vpc_total_sum += $category_part['vision_partial_accessible'];
                    $subcat_vnc_total_sum += $category_part['vision_not_accessible'];
                    $subcat_lfc_total_sum += $category_part['limb_full_accessible'];
                    $subcat_lpc_total_sum += $category_part['limb_partial_accessible'];
                    $subcat_lnc_total_sum += $category_part['limb_not_accessible'];
                    $subcat_hfc_total_sum += $category_part['hearing_full_accessible'];
                    $subcat_hpc_total_sum += $category_part['hearing_partial_accessible'];
                    $subcat_hnc_total_sum += $category_part['hearing_not_accessible'];
                    $subcat_ifc_total_sum += $category_part['intellectual_full_accessible'];
                    $subcat_ipc_total_sum += $category_part['intellectual_partial_accessible'];
                    $subcat_inc_total_sum += $category_part['intellectual_not_accessible'];
                    $subcat_kfc_total_sum += $category_part['kids_full_accessible'];
                    $subcat_kpc_total_sum += $category_part['kids_partial_accessible'];
                    $subcat_knc_total_sum += $category_part['kids_not_accessible'];
                }
                for ($i = 0; $i < 2; $i++) {
                    $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_mfc_total_sum);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_mpc_total_sum);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_mnc_total_sum);
                }
                $cat_mfc_total_sum += $subcat_mfc_total_sum;
                $cat_mpc_total_sum += $subcat_mpc_total_sum;
                $cat_mnc_total_sum += $subcat_mnc_total_sum;

                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_vfc_total_sum);
                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_vpc_total_sum);
                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_vnc_total_sum);
                $cat_vfc_total_sum += $subcat_vfc_total_sum;
                $cat_vpc_total_sum += $subcat_vpc_total_sum;
                $cat_vnc_total_sum += $subcat_vnc_total_sum;

                for ($i = 0; $i < 5; $i++) {
                    $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_lfc_total_sum);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_lpc_total_sum);
                    $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_lnc_total_sum);
                }
                $cat_lfc_total_sum += $subcat_lfc_total_sum;
                $cat_lpc_total_sum += $subcat_lpc_total_sum;
                $cat_lnc_total_sum += $subcat_lnc_total_sum;

                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_hfc_total_sum);
                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_hpc_total_sum);
                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_hnc_total_sum);
                $cat_hfc_total_sum += $subcat_hfc_total_sum;
                $cat_hpc_total_sum += $subcat_hpc_total_sum;
                $cat_hnc_total_sum += $subcat_hnc_total_sum;

                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_ifc_total_sum);
                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_ipc_total_sum);
                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_inc_total_sum);
                $cat_ifc_total_sum += $subcat_ifc_total_sum;
                $cat_ipc_total_sum += $subcat_ipc_total_sum;
                $cat_inc_total_sum += $subcat_inc_total_sum;

                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_kfc_total_sum);
                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_kpc_total_sum);
                $sheet->setCellValueByColumnAndRow($col++, $row, $subcat_knc_total_sum);
                $cat_kfc_total_sum += $subcat_kfc_total_sum;
                $cat_kpc_total_sum += $subcat_kpc_total_sum;
                $cat_knc_total_sum += $subcat_knc_total_sum;

                $subcat_full_access_sum = 2*$subcat_mfc_total_sum + $subcat_vfc_total_sum + 5*$subcat_lfc_total_sum +
                    $subcat_hfc_total_sum + $subcat_ifc_total_sum + $subcat_kfc_total_sum;
                $sheet->setCellValueByColumnAndRow(4, $row, $subcat_full_access_sum);
                $cat_full_acess_total_sum += $subcat_full_access_sum;

                $subcat_part_access_sum = 2*$subcat_mpc_total_sum + $subcat_vpc_total_sum + 5*$subcat_lpc_total_sum +
                    $subcat_hpc_total_sum + $subcat_ipc_total_sum + $subcat_kpc_total_sum;
                $sheet->setCellValueByColumnAndRow(5, $row, $subcat_part_access_sum);
                $cat_part_acess_total_sum += $subcat_part_access_sum;

                $subcat_no_access_sum = 2*$subcat_mnc_total_sum + $subcat_vnc_total_sum + 5*$subcat_lnc_total_sum +
                    $subcat_hnc_total_sum + $subcat_inc_total_sum + $subcat_knc_total_sum;
                $sheet->setCellValueByColumnAndRow(6, $row, $subcat_no_access_sum);
                $cat_no_access_total_sum += $subcat_no_access_sum;

                $subcat_total_sum = $subcat_full_access_sum + $subcat_part_access_sum + $subcat_no_access_sum;
                $sheet->setCellValueByColumnAndRow(3, $row, $subcat_total_sum);
                $cat_total_sum += $subcat_total_sum;

                $row++;
                $sub_id++;
                $col = 2;
            }

            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_full_acess_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_part_acess_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_no_access_total_sum);
            $total_sum += $cat_total_sum;
            $full_acess_total_sum += $cat_full_acess_total_sum;
            $part_acess_total_sum += $cat_part_acess_total_sum;
            $no_access_total_sum += $cat_no_access_total_sum;

            for ($i = 0; $i < 2; $i++) {
                $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_mfc_total_sum);
                $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_mpc_total_sum);
                $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_mnc_total_sum);
            }
            $mfc_total_sum += $cat_mfc_total_sum;
            $mpc_total_sum += $cat_mpc_total_sum;
            $mnc_total_sum += $cat_mnc_total_sum;

            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_vfc_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_vpc_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_vnc_total_sum);
            $vfc_total_sum += $cat_vfc_total_sum;
            $vpc_total_sum += $cat_vpc_total_sum;
            $vnc_total_sum += $cat_vnc_total_sum;

            for ($i = 0; $i < 5; $i++) {
                $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_lfc_total_sum);
                $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_lpc_total_sum);
                $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_lnc_total_sum);
            }
            $lfc_total_sum += $cat_lfc_total_sum;
            $lpc_total_sum += $cat_lpc_total_sum;
            $lnc_total_sum += $cat_lnc_total_sum;

            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_hfc_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_hpc_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_hnc_total_sum);
            $hfc_total_sum += $cat_hfc_total_sum;
            $hpc_total_sum += $cat_hpc_total_sum;
            $hnc_total_sum += $cat_hnc_total_sum;

            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_ifc_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_ipc_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_inc_total_sum);
            $ifc_total_sum += $cat_ifc_total_sum;
            $ipc_total_sum += $cat_ipc_total_sum;
            $inc_total_sum += $cat_inc_total_sum;

            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_kfc_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_kpc_total_sum);
            $sheet->setCellValueByColumnAndRow($cat_col++, $cat_row, $cat_knc_total_sum);
            $kfc_total_sum += $cat_kfc_total_sum;
            $kpc_total_sum += $cat_kpc_total_sum;
            $knc_total_sum += $cat_knc_total_sum;

            $col = 1;
            $id++;
        }

        $col = 3;
        $row = 5;
        $sheet->setCellValueByColumnAndRow($col++, $row, $total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $full_acess_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $part_acess_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $no_access_total_sum);

        for ($i = 0; $i < 2; $i++) {
            $sheet->setCellValueByColumnAndRow($col++, $row, $mfc_total_sum);
            $sheet->setCellValueByColumnAndRow($col++, $row, $mpc_total_sum);
            $sheet->setCellValueByColumnAndRow($col++, $row, $mnc_total_sum);
        }

        $sheet->setCellValueByColumnAndRow($col++, $row, $vfc_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $vpc_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $vnc_total_sum);

        for ($i = 0; $i < 5; $i++) {
            $sheet->setCellValueByColumnAndRow($col++, $row, $lfc_total_sum);
            $sheet->setCellValueByColumnAndRow($col++, $row, $lpc_total_sum);
            $sheet->setCellValueByColumnAndRow($col++, $row, $lnc_total_sum);
        }

        $sheet->setCellValueByColumnAndRow($col++, $row, $hfc_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $hpc_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $hnc_total_sum);

        $sheet->setCellValueByColumnAndRow($col++, $row, $ifc_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $ipc_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $inc_total_sum);

        $sheet->setCellValueByColumnAndRow($col++, $row, $kfc_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $kpc_total_sum);
        $sheet->setCellValueByColumnAndRow($col++, $row, $knc_total_sum);
    }

    private function excelHeader($sheet, $col, $headerRow, $name) {
        $sheet->setCellValueByColumnAndRow($col, $headerRow, $name);
        $sheet->mergeCellsByColumnAndRow($col, $headerRow, $col + 2, $headerRow);
        $sheet->setCellValueByColumnAndRow($col, $headerRow + 1, 'Доступно');
        $sheet->setCellValueByColumnAndRow($col + 1, $headerRow + 1, 'Частично доступно');
        $sheet->setCellValueByColumnAndRow($col + 2, $headerRow + 1, 'Недоступно');
    }
}
