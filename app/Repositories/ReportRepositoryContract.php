<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface ReportRepositoryContract
{
    public function getPnlData(Request $request): array;

    public function getCashFlowData(Request $request): array;
}
