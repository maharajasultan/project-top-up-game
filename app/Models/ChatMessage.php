<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $guarded = [];

    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'chat_conversation_id');
    }

    public function senderAdmin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}