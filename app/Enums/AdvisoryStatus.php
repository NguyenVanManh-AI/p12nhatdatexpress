<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AdvisoryStatus extends Enum
{
    const PENDING = 'Pending';
    const SENDED = 'Sended';
    const FAILED = 'Failed';
}
