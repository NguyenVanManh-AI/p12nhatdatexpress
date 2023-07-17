<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserEnum extends Enum
{
    // number of deleted days when user deleted account
    const DELETED_DAYS = 7;
    const PRUNE_DAYS = 30;
    const BLOCK_DAYS = 7;
    const SPAMMED_DAYS = 1; // spammed for chat
    const NUMBER_BLOCK = 5; 
    const NUMBER_REPORT = 3; // default is 20. 3 for test
    const VERIFY_DEPOSIT_AMOUNT = 500000; // tin chính chủ nạp trên 500 000
}
