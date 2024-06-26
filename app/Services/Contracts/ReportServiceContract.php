<?php

namespace App\Services\Contracts;

interface ReportServiceContract
{
    public function getPnl(array $period): array;

    public function getCashFlow(array $period): array;

    public function getBalanceChanges(array $period): array;

    public function getBalance(array $period): array;
}
