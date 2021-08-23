<?php


namespace App\UserEvents;

interface DataFormatter
{
    public function supports(Data $data): bool;

    public function format(Data $data, Context $context): array;
}
