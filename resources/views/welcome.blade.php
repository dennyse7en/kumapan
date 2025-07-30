<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset(env('APP_LOGO', '/favicon.ico')) }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-[67%_33%]">
        <div class="flex flex-col items-center justify-center p-6 sm:p-12 lg:order-last"
            style="background-color: #6648b0;" x-data="{ isRegistering: true }" x-cloak>
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-8">
                    <img src="{{ asset(env('APP_LOGO', '')) }}" alt="Logo Aplikasi" class="h-24 w-auto">
                </div>


                <div class="text-right mb-6">
                    <button @click.prevent="isRegistering = !isRegistering"
                        class="inline-block px-6 py-2 border-2 border-white text-white font-semibold rounded-md hover:bg-white hover:text-gray-800 transition duration-300"
                        x-text="isRegistering ? 'Masuk' : 'Buat Akun Baru'">
                    </button>
                </div>

                <div x-show="isRegistering">
                    <h1 class="text-3xl font-bold mb-4 text-white">Buat Akun Baru</h1>
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <input id="name" placeholder="Nama"
                            class="block w-full rounded-md shadow-sm border-gray-300" type="text" name="name"
                            required />
                        <input id="email_reg" placeholder="Email"
                            class="block w-full rounded-md shadow-sm border-gray-300" type="email" name="email"
                            required />
                        <input id="password_reg" placeholder="Password"
                            class="block w-full rounded-md shadow-sm border-gray-300" type="password" name="password"
                            required />
                        <input id="password_confirmation" placeholder="Konfirmasi Password"
                            class="block w-full rounded-md shadow-sm border-gray-300" type="password"
                            name="password_confirmation" required />
                        <button type="submit"
                            class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 rounded-md transition duration-300">Daftar
                            Akun</button>
                    </form>
                </div>

                <div x-show="!isRegistering">
                    <h1 class="text-3xl font-bold mb-4 text-white">Masuk</h1>
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <input id="email" placeholder="Email"
                            class="block w-full rounded-md shadow-sm border-gray-300" type="email" name="email"
                            required />
                        <input id="password" placeholder="Password"
                            class="block w-full rounded-md shadow-sm border-gray-300" type="password" name="password"
                            required />
                        <div class="flex items-center justify-between text-sm">
                            <label for="remember_me" class="flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm" name="remember">
                                <span class="ml-2 text-white">Ingat saya</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="underline text-white hover:text-gray-200">
                                Lupa password?
                            </a>
                        </div>
                        <button type="submit"
                            class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 rounded-md">Masuk</button>
                        <a href="#"
                            class="mt-4 w-full flex items-center justify-center py-3 border border-gray-300 rounded-md text-white hover:bg-white hover:text-gray-800 transition duration-300">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48">
                                <path fill="#4285F4"
                                    d="M24 9.5c3.9 0 6.9 1.6 9.1 3.7l6.8-6.8C35.9 2.5 30.3 0 24 0 14.9 0 7.3 5.4 3 13.5l8.4 6.5c1.8-5.4 6.9-9.5 12.6-9.5z">
                                </path>
                                <path fill="#34A853"
                                    d="M46.9 25.6c0-1.7-.2-3.4-.4-5H24v9.5h12.8c-.5 3.1-2.1 5.7-4.4 7.4l7.1 5.5c4.1-3.8 6.5-9.4 6.5-16.4z">
                                </path>
                                <path fill="#FBBC05"
                                    d="M10.8 28.7c-.5-1.5-.8-3.1-.8-4.7s.3-3.2.8-4.7l-8.4-6.5C1.1 16.4 0 20.1 0 24s1.1 7.6 3 11.2l7.8-6.7z">
                                </path>
                                <path fill="#EA4335"
                                    d="M24 48c6.3 0 11.7-2.1 15.6-5.7l-7.1-5.5c-2.1 1.4-4.8 2.2-7.5 2.2-5.7 0-10.8-4.1-12.6-9.5l-8.4 6.5c4.3 8.1 11.9 13.5 21 13.5z">
                                </path>
                                <path fill="none" d="M0 0h48v48H0z"></path>
                            </svg>
                            Masuk dengan Google
                        </a>
                    </form>
                </div>

            </div>
        </div>

        <div class="relative lg:max-h-screen overflow-y-auto no-scrollbar lg:order-first">
            <div class="flex flex-col">
                @forelse($images as $image)
                    <img src="{{ Storage::url($image->path) }}" alt="Informasi Kredit" class="w-full h-auto">
                @empty
                    <div class="flex items-center justify-center h-screen bg-gray-200">
                        <span class="text-gray-500 text-2xl">Tidak ada gambar</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</body>

</html>
