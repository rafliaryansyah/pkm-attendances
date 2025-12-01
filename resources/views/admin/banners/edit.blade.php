<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Banner
            </h2>
            <a href="{{ route('admin.banners.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Banner Aktif (selain ini): <span class="font-bold">{{ $activeBannersCount }}/10</span></p>
                        @if($activeBannersCount >= 10 && !$banner->is_active)
                        <p class="text-sm text-red-600 mt-2">Maksimal banner aktif tercapai. Nonaktifkan banner lain untuk mengaktifkan yang ini.</p>
                        @endif
                    </div>

                    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Banner</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Banner</label>
                            <div class="mb-2 p-3 bg-blue-50 border border-blue-200 rounded">
                                <p class="text-sm text-blue-800 font-semibold">Spesifikasi Gambar:</p>
                                <ul class="text-xs text-blue-700 mt-1 ml-4 list-disc">
                                    <li>Format: JPEG, JPG, PNG</li>
                                    <li>Ukuran Maksimal: 2MB</li>
                                    <li>Rasio Rekomendasi: 16:9 (misal: 1920x1080, 1280x720)</li>
                                    <li>Resolusi Minimum: 800x450 piksel</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-2">Gambar Saat Ini:</p>
                                <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" class="max-w-md h-auto rounded-lg shadow-md">
                            </div>

                            <input type="file" name="image" id="image" accept="image/jpeg,image/jpg,image/png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   onchange="previewImage(event)">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                            @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div id="imagePreview" class="mt-3 hidden">
                                <p class="text-sm text-gray-600 mb-2">Preview Gambar Baru:</p>
                                <img id="preview" src="" alt="Preview" class="max-w-full h-auto rounded-lg shadow-md">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
                            <input type="number" name="order" id="order" value="{{ old('order', $banner->order) }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            <p class="text-xs text-gray-500 mt-1">Semakin kecil angka, semakin awal ditampilkan</p>
                            @error('order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       {{ $activeBannersCount >= 10 && !$banner->is_active ? 'disabled' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Aktifkan Banner</span>
                            </label>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                            <a href="{{ route('admin.banners.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-admin-layout>
