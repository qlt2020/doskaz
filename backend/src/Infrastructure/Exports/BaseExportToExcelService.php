<?php

namespace App\Infrastructure\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

abstract class BaseExportToExcelService {

    protected Spreadsheet $spreadsheet;
    protected $date;

    public function __construct() {
        $this->spreadsheet =  new Spreadsheet();
        $this->date = date('d.m.Y');
    }

    public abstract function fillData(array $data);

    public function writeFile(): string
    {
        try {
            $writer = new Xlsx($this->spreadsheet);
            $filePath = 'storage/' . bin2hex(random_bytes(8)) . '.xlsx';
            $writer->save($filePath);
            return $filePath;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

}
