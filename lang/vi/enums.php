<?php declare(strict_types=1);

use App\Enums\UserVoucherStatusEnum;

return [
    UserVoucherStatusEnum::class => [
        UserVoucherStatusEnum::ACTIVE => 'Có thể sử dụng',
        UserVoucherStatusEnum::PENDING => 'Đang chờ',
        UserVoucherStatusEnum::USED => 'Đã sử dụng',
    ]
];