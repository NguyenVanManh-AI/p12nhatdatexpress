<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class UserVoucherStatusEnum extends Enum implements LocalizedEnum
{
    const ACTIVE = 0;
    const PENDING = 1;
    const USED = 2;
}
