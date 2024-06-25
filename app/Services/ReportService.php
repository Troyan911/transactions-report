<?php

namespace App\Services;

use App\Enums\CashType;
use App\Enums\OperationType;
use App\Models\Transaction;
use App\Services\Contracts\ReportServiceContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportService implements ReportServiceContract
{
    public function getCashFlowData(Request $request): array
    {
        $debitCodes = [OperationType::CASH];
        $creditCodes = [OperationType::CASH];

        $queryValues = $this->getData($request, $debitCodes, $creditCodes, 'cash_operation');

        foreach (CashType::cases() as $type) {
            $resultValues[$type->value] = $queryValues[$type->name] ?? 0;
        }
        $resultValues['net_cf'] = array_sum($resultValues);

        return $resultValues;
    }

    public function getPnlData(Request $request): array
    {
        $debitCodes = [OperationType::COS, OperationType::EXP, OperationType::DIV];
        $creditCodes = [OperationType::REV];

        $queryValues = $this->getData($request, $debitCodes, $creditCodes, 'code');

        //Map query result to all PnL types
        $pnlCodes = array_merge($debitCodes, $creditCodes);
        foreach ($pnlCodes as $type) {
            $resultValues[$type->value] = $queryValues[$type->name] ?? 0;
        }

        //Calc additional PnL values
        $resultValues['operational_profit'] = $resultValues['revenue'] - $resultValues['cost_of_sales'];
        $resultValues['total_costs'] = $resultValues['expenditures'] + $resultValues['cost_of_sales'];
        $resultValues['net_profit'] = $resultValues['revenue'] - $resultValues['total_costs'];
        $resultValues['undivided_profit'] = $resultValues['net_profit'] - $resultValues['dividends'];

        return $resultValues;
    }

    private function getData(Request $request, $debitCodes, $creditCodes, $fieldForGrouping)
    {
        $startDate = $request->exists('start_date') ? $request->input('start_date') : Transaction::min('timestamp');
        $endDate = $request->exists('end_date') ? $request->input('end_date') : now()->format('Y-m-d H:i:s');

        $debitCodesVals = '"'.implode('","', $this->enumArrToNameArr($debitCodes)).'"';
        $creditCodesVals = '"'.implode('","', $this->enumArrToNameArr($creditCodes)).'"';

        //todo between

        $sql = "SELECT SUM(trt.amount) as amount, $fieldForGrouping as code
        FROM (
                SELECT t.amount, tt.debit as code, tt.cash_operation FROM transactions t
                LEFT JOIN transaction_types tt ON tt.type = t.type
                    AND timestamp BETWEEN ? AND ?
                WHERE tt.debit IN ($debitCodesVals)
                UNION ALL
                SELECT -t.amount, tt.credit, tt.cash_operation FROM transactions t
                LEFT JOIN transaction_types tt ON tt.type = t.type
                    AND timestamp BETWEEN ? AND ?
                WHERE tt.credit IN ($creditCodesVals)
            ) trt
        GROUP BY $fieldForGrouping";

        $bindings = [$startDate, $endDate, $startDate, $endDate];
        $queryResults = DB::select($sql, $bindings);

        //Convert result to array as key => value
        $queryValues = array_reduce($queryResults, function ($carry, $item) {
            $carry[$item->code] = floatval($item->amount);

            return $carry;
        }, []);

        return $queryValues;
    }

    private function enumArrToNameArr($enums): array
    {
        $nameArr = [];
        foreach ($enums as $enum) {
            $nameArr[] = $enum->name;
        }

        return $nameArr;
    }
}
