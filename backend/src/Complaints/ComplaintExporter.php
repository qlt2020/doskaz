<?php


namespace App\Complaints;

interface ComplaintExporter
{
    public function execute(int $id, string $locale): \SplFileObject;
}
