<div class="min-h-screen bg-gray-50 p-4">
    <div class="max-w-md mx-auto space-y-4">
        <h2 class="text-2xl font-bold text-gray-800">Pengajuan Izin/Sakit</h2>

        @if($message)
        <div class="p-4 rounded-lg {{ $status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $message }}
        </div>
        @endif

        <form wire:submit.prevent="submit" class="bg-white p-6 rounded-lg shadow space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                <select wire:model="type" class="w-full border-gray-300 rounded-lg">
                    <option value="permit">Izin</option>
                    <option value="sick">Sakit</option>
                </select>
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" wire:model="start_date" class="w-full border-gray-300 rounded-lg">
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" wire:model="end_date" class="w-full border-gray-300 rounded-lg">
                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan</label>
                <textarea wire:model="reason" rows="4" class="w-full border-gray-300 rounded-lg"></textarea>
                @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran (Opsional)</label>
                <input type="file" wire:model="attachment" class="w-full">
                @error('attachment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg">
                Kirim Pengajuan
            </button>
        </form>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold mb-4">Riwayat Pengajuan</h3>
            <div class="space-y-3">
                @forelse($permits as $permit)
                <div class="border-b pb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium">{{ ucfirst($permit->type) }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $permit->start_date->format('d M Y') }} - {{ $permit->end_date->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">{{ $permit->reason }}</p>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($permit->status === 'approved') bg-green-100 text-green-800
                            @elseif($permit->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($permit->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada pengajuan</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
