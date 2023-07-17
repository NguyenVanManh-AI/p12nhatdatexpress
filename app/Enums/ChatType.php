<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ChatType extends Enum
{
    const ACCOUNT = 'Tài khoản';
    const ADD_CLASSIFIED = 'Đăng tin';
    const DEPOSIT = 'Nạp tiền';
    const INVOICE = 'Hóa đơn';
    const OTHER = 'Khác';
    const SYSTEM = 'Hệ thống';
}
