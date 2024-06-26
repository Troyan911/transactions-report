<?php

namespace App\Enums;

enum OperationType: string
{
    //pnl
    case REV = 'revenue';
    case COS = 'cost_of_sales';
    case EXP = 'expenditures';
    case DIV = 'dividends';

    //balance debit
    case CASH = 'cash';
    case ACC = 'accounts_receivable';
    case BAD = 'bad_debt';
    case PPE = 'property_plant_equipment';
    case OA = 'other_assets';

    //balance credit
    case SAL = 'salaries_payable';
    case PAY = 'accounts_payable';
    case DEBT = 'debt';
    case ALL = 'allowance';
    case EQ = 'share_equity';
}
