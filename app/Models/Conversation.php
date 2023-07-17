<?php

namespace App\Models;

use App\Enums\ChatStatus;
use App\Enums\ConversationEnum;
use App\Traits\Models\AdminHistoryTrait;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id', 'receiver_id', 'admin_id', 'type', 'is_support', 'token', 'rating',
        'status', 'options', 'spammed_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'spammed_at' => 'datetime',
    ];

    /**
     * relationships
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function receiver(): BelongsTo
    // {
    //     if ($this->is_support) {
    //         return $this->belongsTo(Admin::class, 'admin_id');
    //     } else {
    //         return $this->belongsTo(User::class, 'receiver_id');
    //     }
    // }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class);
    }

    public function getOtherUserChat($user)
    {
        // return supporter if user open support chat
        if ($this->is_support && get_class($user) == User::class) {
            return $this->admin;
        }

        return $this->users()
            ->when(get_class($user) == User::class, function ($query) use ($user) {
                return $query->where('users.id', '!=', $user->id);
            })
            ->first();
    }

    public function getLastMessage()
    {
        $lastMessage = $this->messages()
                        ->latest('id')
                        ->where(function($query) {
                            return $query->whereJsonDoesntContain('options', ['removed' => true])
                                    ->orWhereNull('options');
                        })
                        ->first();

        return $lastMessage ?? null;
    }

    public function getFirstMessage()
    {
        $firstMessage = $this->messages()
                        ->oldest('id')
                        ->where(function($query) {
                            return $query->whereJsonDoesntContain('options', ['removed' => true])
                                    ->orWhereNull('options');
                        })
                        ->first();

        return $firstMessage ?? null;
    }

    public function getFirstAdminReplyMessage()
    {
        $firstMessage = $this->messages()
                        ->oldest('id')
                        ->where(function($query) {
                            return $query->whereJsonDoesntContain('options', ['removed' => true])
                                    ->orWhereNull('options');
                        })
                        ->where('senderable_type', Admin::class)
                        ->first();

        return $firstMessage ?? null;
    }

    public function getOtherUserLastReadMessage($user)
    {
        $lastReadMessage = $this->messages()
            ->where('senderable_id', $user->id)
            ->where('senderable_type', get_class($user))
            ->where('read', 1)
            ->latest('id')
            ->first();

        return $lastReadMessage ?? null;
    }

    public function getLastMessageOfUser($user)
    {
        if (!$user) return null;

        $lastMessage = $this->messages()
                        ->latest('id')
                        ->whereNull('options->users_deleted->' . $user->id)
                        ->where(function($query) use ($user) {
                            return $query->whereJsonDoesntContain('options', ['user_deleted' => $user->id])
                                    ->whereJsonDoesntContain('options', ['removed' => true])
                                    ->orWhereNull('options');
                        })
                        ->first();
        
        return $lastMessage ?? null;
    }

    public function getUnreadMessage($user)
    {
        // check admin
        $unreadMessagesCount = 0;

        if ($user) {
            $isSupporter = get_class($user) == Admin::class;
            $unreadMessagesCount += $this->messages()
                ->where(function ($query) use ($user, $isSupporter) {
                    if ($isSupporter) {
                        return $query->where('senderable_type', '!=', get_class($user));
                    } else {
                        // should check
                        return $query->where('senderable_id', '!=', $user->id)
                            ->orWhere('senderable_type', '!=', get_class($user));
                    }
                })
                ->where('read', 0)
                ->count();
        }

        return $unreadMessagesCount;
    }

    public function getStatusLabel()
    {
        switch ($this->status) {
            case ChatStatus::PENDING:
                return 'Đang chờ';
                break;
            case ChatStatus::ACTIVE:
                return 'Đang diễn ra';
                break;
            case ChatStatus::ENDED:
                return 'Kết thúc';
                break;
            default:
                break;
        }

        return $this->status;
    }

    /**
     * get type of action chat. user chat to admin or user send request
     */
    public function getChatActionLabel()
    {
        if (!$this->receiver_id) return 'Gửi yêu cầu';
        if ($this->isSupport()) return 'Hỗ trợ riêng';
        
        return 'Tin nhắn riêng'; // user chat with user
    }

    public function remainingSpammedTime()
    {
        if (!$this->isSpammed()) return '0';
        $remainingTime = $this->sender->spammed_at->diffForHumans(now()->addDay(ConversationEnum::SPAMMED_TIME * -1), [
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ]);

        return 'Còn ' . $remainingTime;
    }

    /**
     * Attributes
     */
    public function isSupport()
    {
        return $this->is_support ? true : false;
    }

    public function isEnded()
    {
        return $this->status == ChatStatus::ENDED;
    }

    public function isSpammed()
    {
        return $this->sender && $this->sender->isSpammed();
    }

    public function canChat()
    {
        return !$this->isEnded() && !$this->isSpammed();
    }

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSupport($query, $isSupport = true)
    {
        return $query->where('is_support', $isSupport);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', ChatStatus::ENDED);
    }
}
