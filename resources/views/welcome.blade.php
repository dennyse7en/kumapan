<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-[67%_33%]">
        <div class="flex flex-col items-center justify-center p-6 sm:p-12 lg:order-last" style="background-color: #6648b0;">
            <div class="w-full max-w-md">
                <div class="text-right mb-6">
                    <a href="{{ route('login') }}" class="inline-block px-6 py-2 border-2 border-white text-white font-semibold rounded-md hover:bg-white hover:text-gray-800 transition duration-300">
                        Masuk
                    </a>
                </div>
                
                <h1 class="text-3xl font-bold mb-4 text-white">Buat Akun Baru</h1>
                
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <input id="name" placeholder="Nama" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="name" :value="old('name')" required autofocus />
                    <input id="email" placeholder="Email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="email" name="email" :value="old('email')" required />
                    <input id="password" placeholder="Password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password" required />
                    <input id="password_confirmation" placeholder="Konfirmasi Password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password_confirmation" required />

                    <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 px-4 rounded-md transition duration-300">
                        Daftar Akun
                    </button>
                </form>
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