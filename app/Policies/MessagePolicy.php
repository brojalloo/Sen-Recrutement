<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function markAsRead(User $user, Message $message): bool
    {
        return $message->recipient_id === $user->id;
    }
}
