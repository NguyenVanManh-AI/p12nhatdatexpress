<?php

namespace App\Models;

use App\Enums\ConversationMessageType;
use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class ConversationMessage extends Model
{
    use HasFactory, AdminHistoryTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conversation_id', 'senderable_id', 'senderable_type', 'message', 'read', 'options', 'type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Relationships
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // public function sender()
    // {
    //     return $this->belongsTo(User::class, 'sender_id');
    // }

    /**
     * Get all of the owning commentable models.
     */
    public function senderable()
    {
        return $this->morphTo();
    }

    /**
     * Attributes
     */
    public function getAttachAttribute()
    {
        if ($this->type === ConversationMessageType::ATTACH && File::exists(public_path($this->message))) {
            return asset($this->message);
        }

        return null;
    }

    /**
     * get message is self or not
     * @param $user admin|user
     * 
     * @return bool
     */
    public function isSelf($user): bool
    {
        if (!$user) return false;

        return $this->senderable_id == $user->id
            && $this->senderable_type == get_class($user);
    }
}
