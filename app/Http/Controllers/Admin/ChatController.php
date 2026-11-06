<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatConversation::with('lastMessage')->withCount(['messages as unread_count' => function ($query) {
            $query->where('sender_type', 'customer')->where('is_read', false);
        }]);

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'closed');
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('ticket_id', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%")
                  ->orWhere('customer_email', 'like', "%{$request->search}%");
            });
        }

        $conversations = $query->orderByDesc('updated_at')->paginate(20);

        return view('admin.chat.index', compact('conversations'));
    }

    public function show($id)
    {
        $conversation = ChatConversation::with(['messages.senderAdmin'])->findOrFail($id);
        
        // Mark as read
        ChatMessage::where('chat_conversation_id', $conversation->id)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('conversation'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $conversation = ChatConversation::findOrFail($id);

        if ($conversation->status === 'closed') {
            return back()->with('error', 'Tidak dapat membalas tiket yang sudah ditutup.');
        }

        $message = new ChatMessage();
        $message->chat_conversation_id = $conversation->id;
        $message->sender_type = 'admin';
        $message->admin_id = Auth::guard('admin')->id();
        $message->message = $request->message;
        $message->is_read = false;
        $message->save();

        // Update conversation timestamp
        $conversation->touch();

        return back()->with('success', 'Pesan terkirim.');
    }

    public function close($id)
    {
        $conversation = ChatConversation::findOrFail($id);
        $conversation->status = 'closed';
        $conversation->save();

        return redirect()->route('admin.chat.index')->with('success', 'Tiket percakapan ditutup.');
    }
}
