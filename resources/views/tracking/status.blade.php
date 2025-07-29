<x-guest-layout>
    <div class="w-full max-w-2xl p-6 mx-auto">
        <h2 class="text-2xl font-bold">Riwayat Status untuk Kode: {{ $application->tracking_code }}</h2>
        <p class="text-gray-600 mb-6">Status Terakhir: 
            <span class="font-bold">{{ $application->status }}</span>
        </p>

        <div class="border-l-2 border-gray-200 ml-3">
            @foreach($application->histories->sortByDesc('created_at') as $history)
            <div class="relative mb-8">
                <div class="absolute -left-4 top-1 w-6 h-6 bg-gray-600 rounded-full"></div>
                <div class="ml-8">
                    <p class="font-bold text-lg">{{ $history->status }}</p>
                    <p class="text-sm text-gray-500">{{ $history->created_at->format('d M Y, H:i') }}</p>
                    @if($history->notes)
                    <p class="mt-2 text-gray-700 bg-gray-50 p-3 rounded">Catatan: {{ $history->notes }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <a href="{{ route('track.form') }}" class="text-blue-500 mt-6 inline-block">&larr; Lacak kode lain</a>
    </div>
</x-guest-layout>