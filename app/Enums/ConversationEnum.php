<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ConversationEnum extends Enum
{
    const SPAMMED_TIME = 1; // 1 Day
    const LIMIT_REQUEST_PER_DAY = 3;
}
