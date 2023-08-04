<?php

namespace App\Models;

use App\Enums\UserVoucherStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVoucher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'promotion_id',
        'receipt_date',
        'used_date',
        'status',
    ];

    protected $casts = [
        'used_date' => 'datetime',
        'receipt_date' => 'datetime'
    ];

    // relationships
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    // attributes
    public function getPromotionTypeTitle(): string
    {
        $type = data_get($this->promotion, 'promotion_type');

        return $type
            ? 'Tặng giá trị nạp'
            : 'Giảm giá trị thanh toán';
    }

    public function getPromotionTypePercent(): string
    {
        $type = data_get($this->promotion, 'promotion_type');

        return ($type ? '+' : '-') . data_get($this->promotion, 'value') . '%';
    }

    public function getStatusLabel(): string
    {
        if (!$this->promotion)
            return 'Không tồn tại mã';

        if ($this->promotion->isOutOfUse())
            return 'Hết lượt sử dụng';

        if ($this->promotion->isExpired())
            return 'Hết hạn';

        if ($this->status == UserVoucherStatusEnum::ACTIVE)
            return '';

        return UserVoucherStatusEnum::getDescription($this->status);
    }

    public function getStatusClass(): string
    {
        if (!$this->promotion)
            return 'danger';

        if ($this->promotion->isOutOfUse())
            return 'danger';

        if ($this->promotion->isExpired())
            return 'muted';

        switch ($this->status) {
            case UserVoucherStatusEnum::PENDING:
                return 'warning';
                break;
            case UserVoucherStatusEnum::USED:
                return 'success';
                break;
            default:
                break;
        }

        return '';
    }

    public function canUse(): bool
    {
        // voucher cannot use
        if (!$this->promotion || !$this->promotion->canUse()) return false;

        return $this->status == UserVoucherStatusEnum::ACTIVE;
    }
}
