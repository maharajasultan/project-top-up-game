@extends('layouts.admin')

@section('title', 'Detail Chat')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <a href="{{ route('admin.chat.index') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Daftar Chat</a>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
            Tiket: {{ $conversation->ticket_id }}
            @if($conversation->status === 'open')
                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded-md uppercase tracking-wider">Open</span>
            @else
                <span class="text-[10px] bg-gray-200 text-gray-700 px-2 py-1 rounded-md uppercase tracking-wider">Closed</span>
            @endif
        </h1>
        <p class="text-sm text-gray-500 mt-1">Pelanggan: {{ $conversation->customer_name }} ({{ $conversation->customer_email }})</p>
    </div>
    
    @if($conversation->status === 'open')
    <form action="{{ route('admin.chat.close', $conversation) }}" method="POST" onsubmit="return confirm('Tutup tiket percakapan ini? Anda tidak bisa membalas tiket yang sudah ditutup.');">
        @csrf
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition-colors border border-gray-200">
            Tutup Tiket
        </button>
    </form>
    @endif
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col h-[600px]">
    <!-- Chat Area -->
    <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 bg-slate-50/50" id="chat-container">
        @foreach($conversation->messages as $msg)
            @if($msg->sender_type === 'user')
                <!-- Message from User -->
                <div class="flex items-end gap-3 max-w-[85%] sm:max-w-xl">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                    <div>
                        <div class="bg-white border border-gray-200 text-gray-800 p-3 rounded-2xl rounded-bl-none shadow-sm text-sm">
                            {{ $msg->message }}
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1 ml-1">{{ $msg->created_at->format('H:i, d M') }}</p>
                    </div>
                </div>
            @else
                <!-- Message from Admin -->
                <div class="flex items-end gap-3 max-w-[85%] sm:max-w-xl ml-auto flex-row-reverse">
                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 flex-shrink-0">
                        <i class="fa-solid fa-headset text-xs"></i>
                    </div>
                    <div>
                        <div class="bg-primary-600 text-white p-3 rounded-2xl rounded-br-none shadow-sm text-sm">
                            {{ $msg->message }}
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1 mr-1 text-right">
                            {{ $msg->senderAdmin->name ?? 'Admin' }} • {{ $msg->created_at->format('H:i, d M') }}
                        </p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Reply Area -->
    <div class="p-4 border-t border-gray-100 bg-white rounded-b-2xl">
        @if($conversation->status === 'open')
            <form action="{{ route('admin.chat.reply', $conversation) }}" method="POST" class="flex items-end gap-3">
                @csrf
                <div class="flex-1 relative">
                    <textarea name="message" rows="2" required placeholder="Tulis balasan pesan di sini..."
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
                </div>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold h-[46px] px-6 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 flex-shrink-0">
                    <i class="fa-solid fa-paper-plane"></i> Kirim
                </button>
            </form>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center text-gray-500 text-sm">
                Tiket ini sudah ditutup. Anda tidak dapat mengirim balasan lagi.
            </div>
        @endif
    </div>
</div>

<script>
    // Scroll to bottom of chat automatically
    const chatContainer = document.getElementById('chat-container');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endsection
