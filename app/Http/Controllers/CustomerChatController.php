<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerChatController extends Controller
{
    public function fetch(Request $request)
    {
        $sessionId = $request->session()->getId();
        $email = auth()->check() ? auth()->user()->email : null;

        $conversation = ChatConversation::where(function ($q) use ($sessionId, $email) {
            $q->where('session_id', $sessionId);
            if ($email) {
                $q->orWhere('customer_email', $email);
            }
        })
        ->where('status', '!=', 'closed')
        ->latest('updated_at')
        ->first();

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        // Return messages with a boolean indicating if it's from the customer
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get()->map(function ($msg) {
            return [
                'from' => $msg->sender_type,
                'text' => $msg->message,
                'time' => $msg->created_at->format('H:i')
            ];
        });

        return response()->json([
            'messages' => $messages,
            'ticket_id' => $conversation->ticket_id,
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'name' => 'required|string',
            'contact' => 'required|string',
        ]);

        $sessionId = $request->session()->getId();
        $email = auth()->check() ? auth()->user()->email : $request->contact;
        $name = auth()->check() ? auth()->user()->name : $request->name;

        // Find existing open conversation
        $conversation = ChatConversation::where(function ($q) use ($sessionId, $email) {
            $q->where('session_id', $sessionId);
            if ($email) {
                $q->orWhere('customer_email', $email);
            }
        })
        ->where('status', '!=', 'closed')
        ->latest('updated_at')
        ->first();

        $isFirstMessage = false;
        if (!$conversation) {
            // Create new
            $conversation = ChatConversation::create([
                'ticket_id' => 'TKT-' . strtoupper(Str::random(6)),
                'session_id' => $sessionId,
                'customer_name' => $name,
                'customer_email' => $email,
                'status' => 'open',
                'last_message_at' => now(),
            ]);
            $isFirstMessage = true;
        } else {
            $conversation->update([
                'status' => 'open',
                'last_message_at' => now(),
            ]);
            // If it existed but had no messages, consider it first message
            if ($conversation->messages()->count() === 0) {
                $isFirstMessage = true;
            }
        }

        $msg = ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'sender_type' => 'customer',
            'message' => $request->message,
            'is_read' => false,
        ]);

        if ($isFirstMessage) {
            ChatMessage::create([
                'chat_conversation_id' => $conversation->id,
                'sender_type' => 'admin',
                'message' => 'Halo! Terima kasih telah menghubungi kami. Pesan Anda telah kami terima dan akan kami balas dalam 10 menit di dalam jam kerja.',
                'is_read' => false,
            ]);
        }

        return response()->json([
            'from' => 'customer',
            'text' => $msg->message,
            'time' => $msg->created_at->format('H:i')
        ]);
    }
}
