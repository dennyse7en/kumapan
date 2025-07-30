<x-app-layout>
    <div x-data="{ isModalOpen: false, modalImageUrl: '' }">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pengajuan #{{ $application->tracking_code }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium border-b pb-3 mb-4">Data Pengajuan Lengkap</h3>

                        <h4 class="text-md font-medium mb-4">Data Diri</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">NIK</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->nik }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->phone_number }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Alamat Lengkap</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->address }}</dd>
                            </div>
                        </dl>

                        <hr class="my-6">

                        <h4 class="text-md font-medium mb-4">Data Usaha</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Usaha</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->business_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jenis Usaha</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->business_type }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Alamat Usaha</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->business_address }}</dd>
                            </div>
                        </dl>

                        <hr class="my-6">

                        <h4 class="text-md font-medium mb-4">Detail Pengajuan & Dokumen</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jumlah Pinjaman</dt>
                                <dd class="mt-1 text-sm text-gray-900">Rp
                                    {{ number_format($application->amount, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jangka Waktu</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->tenor }} Bulan</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Foto KTP</dt>
                                <dd class="mt-1">
                                    <img @click="isModalOpen = true; modalImageUrl = '{{ Storage::url($application->ktp_path) }}'"
                                        src="{{ Storage::url($application->ktp_path) }}" alt="Preview KTP"
                                        class="h-24 w-auto rounded-md cursor-pointer hover:opacity-75 transition">
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Foto Tempat Usaha</dt>
                                <dd class="mt-1">
                                    <img @click="isModalOpen = true; modalImageUrl = '{{ Storage::url($application->business_photo_path) }}'"
                                        src="{{ Storage::url($application->business_photo_path) }}"
                                        alt="Preview Tempat Usaha"
                                        class="h-24 w-auto rounded-md cursor-pointer hover:opacity-75 transition">
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit order-first md:order-none">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium border-b pb-3 mb-4">Riwayat Status</h3>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">

                                @foreach ($application->histories->sortBy('created_at') as $history)
                                    <li>
                                        <div class="relative pb-8">

                                            @if (!$loop->last)
                                                <div class="absolute left-1 top-5 -ml-px mt-5">
                                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="relative flex space-x-3">
                                                <div>
                                                    @php
                                                        $iconClass =
                                                            'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white';
                                                        $svgClass = 'h-5 w-5 text-white';
                                                        $bgColor = 'bg-gray-400';
                                                        $icon = 'jam';

                                                        switch ($history->status) {
                                                            case 'Sedang Direview':
                                                                $bgColor = 'bg-gray-400';
                                                                $icon = 'kaca-pembesar';
                                                                break;
                                                            case 'Menunggu Persetujuan':
                                                                $bgColor = 'bg-gray-400';
                                                                $icon = 'centang';
                                                                break;
                                                            case 'Disetujui':
                                                                $bgColor = 'bg-green-500';
                                                                $icon = 'centang';
                                                                break;
                                                            case 'Ditolak':
                                                                $bgColor = 'bg-red-500';
                                                                $icon = 'silang';
                                                                break;
                                                            case 'Lunas':
                                                                $bgColor = 'bg-green-700';
                                                                $icon = 'centang';
                                                                break;
                                                            case 'Dibatalkan':
                                                                $bgColor = 'bg-gray-400';
                                                                $icon = 'silang';
                                                                break;
                                                        }
                                                    @endphp

                                                    <span class="{{ $iconClass }} {{ $bgColor }}">
                                                        @if ($icon === 'jam')
                                                            <svg class="{{ $svgClass }}" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13.25a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V4.75z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @elseif($icon === 'kaca-pembesar')
                                                            <svg class="{{ $svgClass }}" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @elseif($icon === 'centang')
                                                            <svg class="{{ $svgClass }}" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @elseif($icon === 'silang')
                                                            <svg class="{{ $svgClass }}" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path
                                                                    d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                                            </svg>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900">{{ $history->status }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $history->created_at->format('d M Y, H:i') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-75"
            style="display: none;">

            <div @click.away="isModalOpen = false"
                class="relative bg-white rounded-lg shadow-xl max-w-4xl max-h-full overflow-auto">
                <img :src="modalImageUrl" alt="Zoomed Image" class="w-full h-auto">
                <button @click="isModalOpen = false"
                    class="absolute top-2 right-2 text-white bg-gray-800 rounded-full p-2 leading-none">&times;</button>
            </div>
        </div>
    </div>
</x-app-layout>
