<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ReportServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function balance_changes(Request $request)
    {
        //todo
    }

    public function cashFlows(Request $request, ReportServiceContract $repository): JsonResponse
    {
        return response()->json($repository->getCashFlowData($request));
    }

    /**
     * @return JsonResponse
     */
    public function pnl(Request $request, ReportServiceContract $repository)
    {
        return response()->json($repository->getPnlData($request));
    }
}
