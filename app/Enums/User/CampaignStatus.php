<?php

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class CampaignStatus extends Enum
{
    const ACTIVE = 'Active';
    const PENDING = 'Pending';
    const CANCELLED = 'Cancelled';
    const SENDED = 'Sended';
}
