<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatWidgetController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255', // email or phone
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Cari percakapan aktif
        $conversation = ChatConversation::where('customer_email', $request->contact)
                            ->orWhere('customer_phone', $request->contact)
                            ->first();

        if (!$conversation) {
            $isEmail = filter_var($request->contact, FILTER_VALIDATE_EMAIL);
            $conversation = ChatConversation::create([
                'customer_name' => $request->name,
                'customer_email' => $isEmail ? $request->contact : null,
                'customer_phone' => !$isEmail ? $request->contact : null,
                'status' => 'open',
                'last_message_at' => now(),
            ]);
        } else {
            $conversation->update([
                'status' => 'open',
                'last_message_at' => now()
            ]);
        }

        ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'sender_type' => 'customer',
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json(['success' => true, 'message' => 'Pesan terkirim.']);
    }

    public function fetchMessages(Request $request)
    {
        $contact = $request->query('contact');
        if (!$contact) {
            return response()->json(['success' => false, 'messages' => []]);
        }

        $conversation = ChatConversation::where('customer_email', $contact)
                            ->orWhere('customer_phone', $contact)
                            ->first();

        if (!$conversation) {
            return response()->json(['success' => true, 'messages' => []]);
        }

        $messages = ChatMessage::where('chat_conversation_id', $conversation->id)
                        ->orderBy('created_at', 'asc')
                        ->get();

        return response()->json(['success' => true, 'messages' => $messages]);
    }
}
