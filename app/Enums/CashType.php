<?php

namespace App\Enums;

enum CashType: string
{
    case C_OPS = 'cf_operations';
    case C_INV = 'cf_investments';
    case C_FIN = 'cf_financing';
}
