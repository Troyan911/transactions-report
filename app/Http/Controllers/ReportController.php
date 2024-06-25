<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Repositories\ReportRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function balance_changes(Request $request)
    {
        //todo
    }

    /**
     * @param Request $request
     * @param ReportRepositoryContract $repository
     * @return JsonResponse
     */
    public function cashFlows(Request $request, ReportRepositoryContract $repository): JsonResponse
    {
        return response()->json($repository->getCashFlowData($request));
    }

    /**
     * @param Request $request
     * @param ReportRepositoryContract $repository
     * @return JsonResponse
     */
    public function pnl(Request $request, ReportRepositoryContract $repository)
    {
        return response()->json($repository->getPnlData($request));
    }
}
