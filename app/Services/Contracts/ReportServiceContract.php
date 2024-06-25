<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface ReportServiceContract
{
    public function getPnlData(Request $request): array;

    public function getCashFlowData(Request $request): array;
}
