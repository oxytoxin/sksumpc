<?php

namespace App\Enums;

enum PaymentTypes: int
{
    case CASH = 1;
    case JEV = 2;
    case CHECK = 3;
    case ADA = 4;
    case DEPOSIT_SLIP = 5;
    case CDJ = 6;
}
