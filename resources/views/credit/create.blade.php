<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Pengajuan Kredit Usaha Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">Whoops! Ada yang salah.</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('credit.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-lg font-medium mb-4 border-b pb-2">Data Diri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="full_name" class="block font-medium text-sm text-gray-700">Nama
                                    Lengkap</label>
                                <input id="full_name" name="full_name" type="text" class="block mt-1 w-full"
                                    value="{{ old('full_name', auth()->user()->name) }}" required>
                            </div>
                            <div>
                                <label for="nik" class="block font-medium text-sm text-gray-700">Nomor Induk
                                    Kependudukan (NIK)</label>
                                <input id="nik" name="nik" type="text" class="block mt-1 w-full"
                                    value="{{ old('nik') }}" required>
                            </div>
                            <div>
                                <label for="phone_number" class="block font-medium text-sm text-gray-700">Nomor
                                    Telepon</label>
                                <input id="phone_number" name="phone_number" type="text" class="block mt-1 w-full"
                                    value="{{ old('phone_number') }}" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="address" class="block font-medium text-sm text-gray-700">Alamat
                                    Lengkap</label>
                                <textarea id="address" name="address" class="block mt-1 w-full" required>{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium mb-4 border-b pb-2">Data Usaha</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="business_name" class="block font-medium text-sm text-gray-700">Nama
                                    Usaha</label>
                                <input id="business_name" name="business_name" type="text" class="block mt-1 w-full"
                                    value="{{ old('business_name') }}" required>
                            </div>
                            <div>
                                <label for="business_type" class="block font-medium text-sm text-gray-700">Jenis
                                    Usaha</label>
                                <input id="business_type" name="business_type" type="text" class="block mt-1 w-full"
                                    value="{{ old('business_type') }}" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="business_address" class="block font-medium text-sm text-gray-700">Alamat
                                    Usaha</label>
                                <textarea id="business_address" name="business_address" class="block mt-1 w-full" required>{{ old('business_address') }}</textarea>
                            </div>
                        </div>


                        <h3 class="text-lg font-medium mb-4 border-b pb-2">Detail Pengajuan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div x-data="{ amount: '{{ old('amount_raw', '') }}', formattedAmount: '' }" x-init="formattedAmount = new Intl.NumberFormat('id-ID').format(amount || 0);
                            $watch('amount', value => formattedAmount = new Intl.NumberFormat('id-ID').format(value || 0))">
                                <label for="amount_display" class="block font-medium text-sm text-gray-700">Jumlah
                                    Pinjaman (Rp)</label>
                                <input id="amount_display" type="text" x-model="formattedAmount"
                                    @input="amount = $event.target.value.replace(/\./g, '')" class="block mt-1 w-full"
                                    required>
                                <input type="hidden" name="amount" x-model="amount">
                            </div>
                            <div>
                                <label for="tenor" class="block font-medium text-sm text-gray-700">Jangka Waktu
                                    (Bulan)</label>
                                <select id="tenor" name="tenor" class="block mt-1 w-full" required>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">12 Bulan</option>
                                    <option value="18">18 Bulan</option>
                                    <option value="24">24 Bulan</option>
                                </select>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium mb-4 border-b pb-2">Upload Dokumen</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="ktp_path" class="block font-medium text-sm text-gray-700">Foto KTP</label>
                                <input id="ktp_path" name="ktp_path" type="file" class="block mt-1 w-full" required>
                            </div>
                            <div>
                                <label for="business_photo_path" class="block font-medium text-sm text-gray-700">Foto
                                    Tempat Usaha</label>
                                <input id="business_photo_path" name="business_photo_path" type="file"
                                    class="block mt-1 w-full" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
