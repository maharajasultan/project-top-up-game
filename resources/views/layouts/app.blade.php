<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Maullstore - Platform Top Up Game & Jasa Joki terpercaya untuk Mobile Legends, Free Fire, dan PUBG Mobile.')">
    <title>@yield('title', 'Maullstore') — Top Up Game & Jasa Joki</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50:  '#EEF2FF',
                            100: '#E0E7FF',
                            200: '#C7D2FE',
                            300: '#A5B4FC',
                            400: '#818CF8',
                            500: '#6366F1',
                            600: '#4F46E5',
                            700: '#4338CA',
                            800: '#3730A3',
                            900: '#312E81',
                        },
                        accent: '#F59E0B',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.4s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-dot': 'bounceDot 1.4s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        bounceDot: {
                            '0%, 80%, 100%': { transform: 'scale(0)' },
                            '40%': { transform: 'scale(1)' },
                        },
                    },
                }
            }
        }
    </script>

    <!-- Midtrans Snap -->
    @if(config('services.midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }

        /* Scrollbar Custom */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #4F46E5; border-radius: 3px; }

        /* Chat Widget */
        .chat-widget-btn {
            box-shadow: 0 8px 32px rgba(79, 70, 229, 0.35);
            transition: all 0.3s ease;
        }
        .chat-widget-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 12px 40px rgba(79, 70, 229, 0.5);
        }

        /* Card hover */
        .game-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .game-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.15);
        }

        /* Navbar scroll effect handled by Alpine */
        .navbar-shadow { box-shadow: 0 2px 20px rgba(0,0,0,0.08); }

        /* Product card selected */
        .product-card.selected {
            border-color: #4F46E5;
            background-color: #EEF2FF;
            box-shadow: 0 0 0 2px #4F46E5;
        }

        /* Step indicator */
        .step-active { background: #4F46E5; color: white; }
        .step-done { background: #10B981; color: white; }
        .step-inactive { background: #E5E7EB; color: #9CA3AF; }

        /* Typing dots */
        .typing-dot:nth-child(1) { animation: bounceDot 1.4s ease-in-out 0s infinite; }
        .typing-dot:nth-child(2) { animation: bounceDot 1.4s ease-in-out 0.2s infinite; }
        .typing-dot:nth-child(3) { animation: bounceDot 1.4s ease-in-out 0.4s infinite; }

        @keyframes bounceDot {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Badge badge */
        .badge-discount {
            background: linear-gradient(135deg, #EF4444, #F97316);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-white text-gray-900 antialiased">

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Live Chat Widget -->
    @include('components.chat-widget')

    @stack('scripts')
</body>
</html>
