<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Maullstore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 font-sans text-gray-900">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-100 p-8 sm:p-10">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-primary-600 mb-2">Maullstore Admin</h1>
            <p class="text-sm text-gray-500">Silakan login untuk mengelola sistem</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 text-red-600 text-sm p-4 rounded-xl mb-6 flex gap-3 items-start border border-red-100">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 text-red-600 text-sm p-4 rounded-xl mb-6 flex gap-3 items-start border border-red-100">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all"
                        placeholder="admin@maullstore.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" required
                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 cursor-pointer">
                    <span class="text-sm text-gray-600 font-medium">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg flex justify-center items-center gap-2">
                Login <i class="fa-solid fa-arrow-right text-sm"></i>
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="/" class="text-sm text-gray-400 hover:text-primary-600 font-medium transition-colors">
                &larr; Kembali ke Website
            </a>
        </div>
    </div>
</body>
</html>
