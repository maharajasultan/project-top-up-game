<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    protected $guarded = [];
    protected $casts   = ['last_message_at' => 'datetime'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }
}