<?php

namespace App\Models;

use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected $table = 'admin';

    protected $fillable = [
        'admin_username',
        'admin_fullname',
        'password',
        'admin_email',
        'admin_birthday',
        'image_url',
        'admin_type',
        'rol_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_at',
        'is_deleted',
        'is_online',
        'rating',
        'is_customer_care'
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->admin_email;
    }

    // relationships
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function conversationMessages(): MorphMany
    {
        return $this->morphMany(ConversationMessage::class, 'senderable');
    }

    /**
     * Attributes
     */
    public function getAvatarUrl()
    {
        return $this->image_url && File::exists(public_path("/system/img/avatar-admin/$this->image_url"))
            ? asset("/system/img/avatar-admin/$this->image_url")
            : asset('/images/icons/care-staff.jpg');
    }

    /**
     * Get available status
     * @return String
     */
    public function getAvailableStatus()
    {
        return 'online';
        
        if ($this->is_online) {
            return 'online';
        }

        return 'offline';
        // else  if ($this->last_login_at && $this->last_login_at->diffInSeconds() < 900) {
        //     return 'recently';
        // } else if ($this->last_login_at && $this->last_login_at->diffInSeconds() < 10800) {
        //     return 'away';
        // }
    }

    /**
     * Get last rating of the user for support. by default is 5 stars.
     * 
     * @return double $rating
     */
    public function getLastCustomerChatRating($userId)
    {
        $lastConversation = Conversation::support()
            ->where([
                'receiver_id' => $this->id,
                'sender_id' => $userId
            ])
            ->first();

        return data_get($lastConversation, 'rating') ?: 5;
    }

    public function getLastActiveConversationToken($userId)
    {
        $lastActiveConversation = Conversation::support()
            ->where([
                'receiver_id' => $this->id,
                'sender_id' => $userId,
            ])
            ->active()
            ->first();

        return data_get($lastActiveConversation, 'token');
    }

    public function getFullName()
    {
        return $this->admin_fullname;
    }
    
    public function getChatName()
    {
        return $this->getFullName() ?: 'Hỗ trợ kỹ thuật';
    }

    public function getChannelTokenAttribute($value)
    {
        $token = $value;

        if (!$value) {
            $token = md5($this->id . uniqid() . date('d-m-Y H:i:s'));
            $this->channel_token = $token;
            $this->save();
        }

        return $token;
        // return $this->isSupport() ? 'support_channel' : null;
    }

    public function getUnreadMessages()
    {
        $messages = ConversationMessage::selectRaw('conversation_messages.*')
                        ->where('conversation_messages.senderable_type', '!=', self::class)
                        ->join('conversations', 'conversations.id', '=', 'conversation_messages.conversation_id')
                        ->join('conversation_user', 'conversation_user.conversation_id', '=', 'conversations.id')
                        // ->where('conversation_user.user_id', $this->id)
                        ->where('conversations.is_support', true)
                        ->where('read', false)
                        ->count();

        return $messages;
    }

    public function canChat()
    {
        return $this->isSupport();
    }

    public function isSupport(): bool
    {
        // should check support permission
        return true;
    }
}
