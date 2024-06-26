<?php

namespace App\Services\Contracts;

interface ImportCsvServiceContract
{
    public function importTransactions(string $filepath);

    public function importTransactionTypes(string $filepath);

    public function importOperationTypes(string $filepath);
}
