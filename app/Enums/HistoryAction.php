<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HistoryAction extends Enum
{
    const CREATED = 'Created';
    const UPDATED = 'Updated';
    const DELETED = 'Deleted';
    const RESTORED = 'Restored';
    const FORCE_DELETED = 'ForceDeleted';
}
