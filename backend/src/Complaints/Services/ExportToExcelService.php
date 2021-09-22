<?php

namespace App\Complaints\Services;

use App\Infrastructure\Exports\BaseExportToExcelService;
use Symfony\Component\HttpFoundation\Request;

class ExportToExcelService extends BaseExportToExcelService
{

    private string $period;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->period = sprintf("Дата( %s - %s )", date('Y-m-d',strtotime($request->query->getAlnum('dateFrom'))),
            date('Y-m-d',strtotime($request->query->getAlnum('dateTo'))));
    }

    public function fillData(array $data)
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        $sheet->getStyle('A2:D1')->getFont()->setBold(true);
        $sheet->setCellValueByColumnAndRow(2, 1, 'Весь РК / ГРЗ / ГОЗ / Города');
        $sheet->setCellValueByColumnAndRow(4, 1, $this->period);
        $sheet->setCellValueByColumnAndRow(2, 2, 'Дата: ' . date('d.m.Y'));
        $sheet->setCellValueByColumnAndRow(1, 3, '#');
        $sheet->setCellValueByColumnAndRow(2, 3, 'Город');
        $sheet->setCellValueByColumnAndRow(3, 3, 'Жалобы');
        $sheet->setCellValueByColumnAndRow(4, 3, 'Обращения');

        $col = 1;
        $row = 4;
        $id = 0;

        foreach ($data as $datum) {
            $sheet->setCellValueByColumnAndRow($col++, $row, $id++);
            $sheet->setCellValueByColumnAndRow($col++, $row, $datum['city_name']);
            $sheet->setCellValueByColumnAndRow($col++, $row, $datum['complaint_count']);
            $sheet->setCellValueByColumnAndRow($col++, $row, $datum['feedback_count']);
            $row++;
            $col = 1;
        }
    }

}
