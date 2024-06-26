<?php

namespace App\Services;

use App\Enums\CashType;
use App\Enums\OperationType as OType;
use App\Services\Contracts\ReportServiceContract;
use Illuminate\Support\Facades\DB;

class ReportService implements ReportServiceContract
{
    public function getBalance(array $period): array
    {
        return $this->getBalanceData($period);
    }

    public function getBalanceChanges(array $period): array
    {
        return $this->getBalanceData($period);
    }

    private function getBalanceData(array $period): array
    {
        $balanceCodes = [
            OType::CASH,
            OType::ACC,
            OType::BAD,
            OType::PPE,
            OType::OA,

            OType::SAL,
            OType::PAY,
            OType::DEBT,
            OType::ALL,

            OType::EQ,
        ];

        $queryValues = $this->getDataFromTransactions($period, $balanceCodes, 'code');
        foreach ($balanceCodes as $type) {
            $resultValues[$type->value] = $queryValues[$type->name] ?? 0;
        }

        //        total_assets = CASH + ACC + BAD + PPE + OA
        $resultValues['total_assets'] =
            $resultValues[OType::CASH->value]
            + $resultValues[OType::ACC->value]
            + $resultValues[OType::BAD->value]
            + $resultValues[OType::PPE->value]
            + $resultValues[OType::OA->value];

        //        total_liabilities = SAL + PAY + ALL + DEBT
        $resultValues['total_liabilities'] =
            $resultValues[OType::SAL->value]
            + $resultValues[OType::PAY->value]
            + $resultValues[OType::DEBT->value]
            + $resultValues[OType::ALL->value];

        //        undivided_profit Take value from PnL for the same period
        $pnlData = $this->getPnl($period);
        $resultValues['undivided_profit'] = $pnlData['undivided_profit'];

        //        total_equity = share_equity + undivided_income
        $resultValues['total_equity'] = $resultValues[OType::EQ->value] + $resultValues['undivided_profit'];

        return $resultValues;
    }

    public function getCashFlow(array $period): array
    {
        $cashFlowCodes = [OType::CASH];
        $queryValues = $this->getDataFromTransactions($period, $cashFlowCodes, 'cash_operation');

        foreach (CashType::cases() as $type) {
            $resultValues[$type->value] = $queryValues[$type->name] ?? 0;
        }
        $resultValues['net_cf'] =
            $resultValues[CashType::C_FIN->value]
            + $resultValues[CashType::C_INV->value]
            + $resultValues[CashType::C_OPS->value];

        return $resultValues;
    }

    public function getPnl(array $period): array
    {
        $pnlCodes = [OType::COS, OType::EXP, OType::DIV, OType::REV];
        $queryValues = $this->getDataFromTransactions($period, $pnlCodes, 'code');

        //Map query result to all PnL types
        foreach ($pnlCodes as $type) {
            $resultValues[$type->value] = $queryValues[$type->name] ?? 0;
        }

        //Calc additional PnL values
        $resultValues['operational_profit'] = $resultValues[OType::REV->value] - $resultValues[OType::COS->value];
        $resultValues['total_costs'] = $resultValues[OType::EXP->value] + $resultValues[OType::COS->value];
        $resultValues['net_profit'] = $resultValues[OType::REV->value] - ($resultValues[OType::EXP->value] + $resultValues[OType::COS->value]);
        $resultValues['undivided_profit'] = $resultValues['net_profit'] - $resultValues[OType::DIV->value];

        return $resultValues;
    }

    /**
     * @param  $pnlCodes
     * @return mixed
     */
    private function getDataFromTransactions(array $period, $operationTypeCodes, $fieldForGrouping)
    {
        $startDate = $period['start_date'];
        $endDate = $period['end_date'];

        $operationTypeCodesVals = '"'.implode('","', $this->enumArrToNameArr($operationTypeCodes)).'"';

        $sql = "SELECT SUM(trt.amount) as amount, $fieldForGrouping as code
        FROM (
            SELECT t.amount * ot.debit as amount,
                   tt.debit as code,
                   tt.cash_operation
            FROM transactions t
            LEFT JOIN transaction_types tt ON tt.type = t.type
            LEFT JOIN operation_types ot ON tt.debit = ot.code
            WHERE t.timestamp between ? AND ?
              AND tt.debit IN ($operationTypeCodesVals)
            UNION ALL
            SELECT t.amount * ot.credit as amount,
                   tt.credit as code,
                   tt.cash_operation
            FROM transactions t
            LEFT JOIN transaction_types tt ON tt.type = t.type
            LEFT JOIN operation_types ot ON tt.credit = ot.code
            WHERE t.timestamp between ? AND ?
            AND tt.credit IN ($operationTypeCodesVals)
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
