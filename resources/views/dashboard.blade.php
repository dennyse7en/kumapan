<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div x-data="{
        isModalOpen: false,
        cancelUrl: '',
        openModal(event) {
            this.cancelUrl = event.currentTarget.getAttribute('data-url');
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
        }
    }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Riwayat Pengajuan Kredit Anda</h3>
                            <a href="{{ $canApply ? route('credit.create') : '#' }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                      {{ !$canApply ? 'opacity-50 cursor-not-allowed' : '' }}"
                               @if(!$canApply) onclick="event.preventDefault(); alert('Anda tidak dapat membuat pengajuan baru karena masih ada pengajuan yang aktif.');" @endif>
                                Buat Pengajuan Baru
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                         @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 ...">ID</th>
                                    <th class="px-6 py-3 ...">Tanggal</th>
                                    <th class="px-6 py-3 ...">Jumlah</th>
                                    <th class="px-6 py-3 ...">Status</th>
                                    <th class="px-6 py-3 ...">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($applications as $app)
                                    <tr>
                                        <td class="px-6 py-4 ...">#{{ $app->id }}</td>
                                        <td class="px-6 py-4 ...">{{ $app->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 ...">Rp {{ number_format($app->amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 ...">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($app->status == 'Disetujui' || $app->status == 'Lunas') bg-green-100 text-green-800
                                                @elseif(in_array($app->status, ['Ditolak', 'Dibatalkan'])) bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ $app->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('credit.show', $app) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                            
                                            @if(!in_array($app->status, ['Disetujui', 'Ditolak', 'Lunas', 'Dibatalkan']))
                                                <button @click="openModal"
                                                        data-url="{{ route('credit.cancel', $app) }}"
                                                        class="text-red-600 hover:text-red-900 ml-4">Batalkan</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center ...">Anda belum memiliki pengajuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="isModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
             style="display: none;">
            <div @click.away="closeModal" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
                <form :action="cancelUrl" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <h3 class="text-lg font-medium text-gray-900">Batalkan Pengajuan</h3>
                    <p class="mt-2 text-sm text-gray-600">Anda yakin ingin membatalkan pengajuan ini? Mohon berikan alasan pembatalan.</p>
                    
                    <div class="mt-4">
                        <label for="cancellation_reason" class="sr-only">Alasan Pembatalan</label>
                        <textarea id="cancellation_reason" name="cancellation_reason" rows="3"
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Contoh: Saya tidak jadi mengajukan pinjaman." required></textarea>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Tutup
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                            Ya, Batalkan Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>