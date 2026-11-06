<!-- Live Chat Widget -->
<div x-data="{
    open: false,
    step: '{{ auth()->check() ? 'chat' : 'form' }}',
    name: '{{ auth()->check() ? auth()->user()->name : '' }}',
    contact: '{{ auth()->check() ? auth()->user()->email : '' }}',
    message: '',
    messages: [],
    sending: false,
    unread: 1,
    loaded: false,

    init() {
        // Fetch periodically when open
        setInterval(() => {
            if (this.open) {
                this.fetchMessages(false);
            }
        }, 5000);
    },

    async fetchMessages(scrollToBottom = true) {
        try {
            let res = await fetch('/api/chat/fetch');
            let data = await res.json();
            
            // Check if there are messages
            if (data.messages && data.messages.length > 0) {
                let isNewMessage = this.messages.length !== data.messages.length;
                this.messages = data.messages;
                this.step = 'chat';
                
                if (scrollToBottom && isNewMessage) {
                    this.$nextTick(() => { this.scrollToBottom(); });
                }
            } else if (this.step === 'chat' && this.messages.length === 0) {
                this.messages = [{ from: 'admin', text: 'Halo ' + (this.name || 'Kak') + '! 👋 Ada yang bisa kami bantu?', time: new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) }];
            }
        } catch(e) {
            console.error('Failed to fetch chat', e);
        }
    },

    async sendMessage() {
        if (!this.message.trim()) return;
        
        let text = this.message;
        this.messages.push({ from: 'customer', text: text, time: new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) });
        this.message = '';
        this.sending = true;
        this.$nextTick(() => { this.scrollToBottom(); });

        try {
            await fetch('/api/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: text,
                    name: this.name,
                    contact: this.contact
                })
            });
            // Fetch immediately to confirm
            await this.fetchMessages();
        } catch(e) {
            console.error('Failed to send message', e);
        }
        this.sending = false;
    },

    startChat() {
        if (!this.name.trim() || !this.contact.trim()) return;
        this.step = 'chat';
        this.messages = [{ from: 'admin', text: 'Halo ' + this.name + '! 👋 Ada yang bisa kami bantu?', time: new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) }];
        this.unread = 0;
    },

    scrollToBottom() {
        let el = document.getElementById('chat-messages');
        if (el) el.scrollTop = el.scrollHeight;
    },

    toggleChat() {
        this.open = !this.open;
        this.unread = 0;
        if (this.open && !this.loaded) {
            this.fetchMessages();
            this.loaded = true;
        } else if (this.open) {
            this.$nextTick(() => { this.scrollToBottom(); });
        }
    }
}" class="fixed bottom-5 right-5 z-50 flex flex-col items-end gap-3">

    <!-- Chat Window -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden" style="display:none; max-height: 520px;">

        <!-- Header -->
        <div class="bg-primary-600 px-4 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-headset text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">Live Chat Maullstore</p>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse-slow"></span>
                        <p class="text-primary-200 text-xs">Online — Siap membantu</p>
                    </div>
                </div>
            </div>
            <button @click="open = false" class="text-white/70 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Form Identitas -->
        <div x-show="step === 'form'" class="p-4">
            <p class="text-sm text-gray-600 mb-4">Perkenalkan diri kamu sebelum memulai chat 😊</p>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Kamu</label>
                    <input type="text" x-model="name" placeholder="Masukkan nama..." class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email / No. WhatsApp</label>
                    <input type="text" x-model="contact" placeholder="Email atau nomor HP..." class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
                <button @click="startChat()" :disabled="!name.trim() || !contact.trim()" class="w-full bg-primary-600 hover:bg-primary-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-semibold py-2.5 rounded-xl transition-all">
                    Mulai Chat <i class="fa-solid fa-arrow-right ml-1.5"></i>
                </button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div x-show="step === 'chat'" class="flex flex-col" style="height: 380px;">
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-for="(msg, i) in messages" :key="i">
                    <div :class="msg.from === 'customer' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.from === 'customer' ? 'bg-primary-600 text-white rounded-2xl rounded-br-sm' : 'bg-gray-100 text-gray-800 rounded-2xl rounded-bl-sm'" class="max-w-[80%] px-3.5 py-2.5 shadow-sm">
                            <p class="text-sm break-words" x-text="msg.text"></p>
                            <p class="text-[10px] mt-1 opacity-70 text-right" x-text="msg.time"></p>
                        </div>
                    </div>
                </template>

                <!-- Sending indicator -->
                <div x-show="sending" class="flex justify-start">
                    <div class="bg-gray-100 rounded-2xl rounded-bl-sm px-4 py-3 flex items-center gap-1.5">
                        <span class="typing-dot w-2 h-2 bg-gray-400 rounded-full inline-block"></span>
                        <span class="typing-dot w-2 h-2 bg-gray-400 rounded-full inline-block"></span>
                        <span class="typing-dot w-2 h-2 bg-gray-400 rounded-full inline-block"></span>
                    </div>
                </div>
            </div>

            <!-- Input -->
            <div class="border-t border-gray-100 p-3 flex items-center gap-2 bg-gray-50/50">
                <input type="text" x-model="message" @keydown.enter="sendMessage()" placeholder="Tulis pesan..." class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm">
                <button @click="sendMessage()" :disabled="!message.trim() || sending" class="w-9 h-9 flex-shrink-0 flex items-center justify-center bg-primary-600 hover:bg-primary-700 disabled:bg-gray-300 disabled:text-gray-500 text-white rounded-xl transition-all shadow-sm">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Toggle Button -->
    <button @click="toggleChat()" class="chat-widget-btn relative w-14 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl flex items-center justify-center transition-all">
        <i class="fa-solid fa-comments text-xl" x-show="!open"></i>
        <i class="fa-solid fa-xmark text-xl" x-show="open" style="display:none"></i>
        <!-- Unread Badge -->
        <span x-show="unread > 0 && !open" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 border-2 border-white text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-bounce" x-text="unread"></span>
    </button>
</div>
