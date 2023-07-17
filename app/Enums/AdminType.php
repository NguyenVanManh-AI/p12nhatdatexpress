<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AdminType extends Enum
{
    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const MANAGER = 3;
}
