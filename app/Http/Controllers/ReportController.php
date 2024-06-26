<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\Contracts\ReportServiceContract;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function balance_changes(Request $request, ReportServiceContract $repository): JsonResponse
    {
        $period = $this->setPeriod($request);

        return response()->json($repository->getBalanceChanges($period));
    }

    public function balance(Request $request, ReportServiceContract $repository): JsonResponse
    {
        $period = $this->setPeriodFromStartToDate($request);

        return response()->json($repository->getBalanceChanges($period));
    }

    public function cashFlows(Request $request, ReportServiceContract $repository): JsonResponse
    {
        $period = $this->setPeriod($request);

        return response()->json($repository->getCashFlow($period));
    }

    public function pnl(Request $request, ReportServiceContract $repository): JsonResponse
    {
        $period = $this->setPeriod($request);

        return response()->json($repository->getPnl($period));
    }

    private function setPeriod(Request $request): array
    {
        $start_date = $request->exists('start_date')
            ? $request->input('start_date')
            : Transaction::min('timestamp');
        $end_date = $request->exists('end_date')
            ? (new DateTime($request->input('end_date')))->setTime(23, 59, 59)->format('Y-m-d H:i:s')
            : now()->format('Y-m-d H:i:s');

        return compact('start_date', 'end_date');
    }

    private function setPeriodFromStartToDate(Request $request): array
    {
        $start_date = Transaction::min('timestamp');

        $end_date = $request->exists('date')
            ? (new DateTime($request->input('date')))->setTime(23, 59, 59)->format('Y-m-d H:i:s')
            : now()->format('Y-m-d H:i:s');

        return compact('start_date', 'end_date');
    }
}
