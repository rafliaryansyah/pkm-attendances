<div class="min-h-screen bg-gray-50 p-4">
    <div class="max-w-md mx-auto space-y-4">
        <h2 class="text-2xl font-bold text-gray-800">Pengajuan Revisi Absensi</h2>

        @if($message)
        <div class="p-4 rounded-lg {{ $status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $message }}
        </div>
        @endif

        <form wire:submit.prevent="submit" class="bg-white p-6 rounded-lg shadow space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" wire:model="date" class="w-full border-gray-300 rounded-lg">
                @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Check In</label>
                <input type="time" wire:model="proposed_clock_in" class="w-full border-gray-300 rounded-lg">
                @error('proposed_clock_in') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Check Out</label>
                <input type="time" wire:model="proposed_clock_out" class="w-full border-gray-300 rounded-lg">
                @error('proposed_clock_out') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Revisi</label>
                <textarea wire:model="reason" rows="4" class="w-full border-gray-300 rounded-lg" placeholder="Jelaskan alasan revisi absensi..."></textarea>
                @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg">
                Kirim Pengajuan Revisi
            </button>
        </form>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold mb-4">Riwayat Pengajuan Revisi</h3>
            <div class="space-y-3">
                @forelse($revisions as $revision)
                <div class="border-b pb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium">{{ $revision->date->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $revision->proposed_clock_in->format('H:i') }} - {{ $revision->proposed_clock_out->format('H:i') }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">{{ $revision->reason }}</p>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($revision->status === 'approved') bg-green-100 text-green-800
                            @elseif($revision->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($revision->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada pengajuan revisi</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
