<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ChatStatus extends Enum
{
    const ACTIVE = 'Active'; // Đang chat
    const ENDED = 'Ended'; // Kết thúc
    const PENDING = 'Pending'; // Đang chờ
}
