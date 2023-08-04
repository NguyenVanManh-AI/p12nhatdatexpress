<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AdminActionEnum extends Enum
{
    const DELETE = 'delete';
    const UPDATE = 'update';
    const DUPLICATE = 'duplicate';
    const RESTORE = 'restore';
    const FORCE_DELETE = 'force-delete';
}
