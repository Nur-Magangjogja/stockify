<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Stockify</title>
    <meta name="description" content="Login ke Stockify - Sistem Manajemen Stok Gudang">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: radial-gradient(ellipse at 20% 50%, rgba(37, 99, 235, 0.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(79, 70, 229, 0.08) 0%, transparent 50%),
                        #111827;
        }
        .auth-card {
            background: rgba(31, 41, 55, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(75, 85, 99, 0.5);
        }
        .input-field {
            background: rgba(17, 24, 39, 0.8);
            border: 1px solid rgba(75, 85, 99, 0.6);
            color: #f3f4f6;
            transition: all 0.2s;
        }
        .input-field:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            outline: none;
        }
        .input-field::placeholder { color: #6b7280; }
        .btn-primary {
            background: linear-gradient(135deg, #2563EB, #4F46E5);
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1D4ED8, #4338CA);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white">Stockify</h1>
            <p class="text-gray-400 mt-1 text-sm">Sistem Manajemen Stok Gudang</p>
        </div>

        {{-- Card --}}
        <div class="auth-card rounded-2xl p-8 shadow-2xl">
            <h2 class="text-xl font-semibold text-white mb-6">Masuk ke Akun Anda</h2>

            @if($errors->any())
            <div class="p-3 mb-4 rounded-lg bg-red-900/30 border border-red-700 text-red-300 text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="login-form">
                @csrf
                <div class="space-y-5">
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm @error('email') border-red-500 @enderror">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                placeholder="••••••••"
                                class="input-field w-full pl-10 pr-12 py-3 rounded-xl text-sm">
                            <button type="button" id="toggle-password"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-300">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 7c-3.866 0-7-2.686-7-6s3.134-6 7-6 7 2.686 7 6-3.134 6-7 6z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-blue-600">
                        <label for="remember" class="ml-2 text-sm text-gray-400">Ingat saya</label>
                    </div>

                    <button type="submit" id="submit-btn"
                        class="btn-primary w-full py-3 rounded-xl text-white font-semibold text-sm">
                        Masuk
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-xs text-gray-600 mt-6">
            &copy; {{ date('Y') }} Stockify. All rights reserved.
        </p>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-password');
        const pwdInput  = document.getElementById('password');
        toggleBtn.addEventListener('click', () => {
            pwdInput.type = pwdInput.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>
