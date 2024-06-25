<?php

namespace App\Services\Contracts;

interface ImportCsvServiceContract
{
    public function importTransaction(string $filepath);
}
