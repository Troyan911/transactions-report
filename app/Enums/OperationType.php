<?php

namespace App\Enums;

enum OperationType: string
{
    case REV = 'revenue';
    case COS = 'cost_of_sales';
    case EXP = 'expenditures';
    case DIV = 'dividends';
    case CASH = 'cash';

}
