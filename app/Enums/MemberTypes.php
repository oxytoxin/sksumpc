<?php

namespace App\Enums;

enum MemberTypes: int
{
    case REGULAR = 1;
    case ASSOCIATE = 2;
    case LABORATORY = 3;
    case ORGANIZATION = 4;
}
