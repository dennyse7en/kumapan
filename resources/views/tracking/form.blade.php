{{-- Asumsi Anda punya layout utama `layouts.app` atau sejenisnya --}}
<x-guest-layout>
    <div class="w-full max-w-md p-6 mx-auto">
        <h2 class="text-2xl font-bold text-center">Lacak Status Pengajuan Anda</h2>
        <p class="text-center text-gray-600 mb-6">Masukkan 6 digit kode pelacakan Anda.</p>

        @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('track.status') }}" method="GET">
            <input type="text" name="tracking_code" placeholder="Contoh: A1B2C3" 
                   class="w-full px-4 py-2 border rounded-md" maxlength="6" required>
            <button type="submit" class="w-full mt-4 bg-gray-800 text-white py-2 rounded-md">Lacak</button>
        </form>
    </div>
</x-guest-layout>