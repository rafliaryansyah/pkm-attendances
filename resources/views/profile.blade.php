<x-app-layout>
    <div class="min-h-screen bg-gray-50 p-4">
        <div class="max-w-md mx-auto space-y-4">
            <h2 class="text-2xl font-bold text-gray-800">Profile</h2>

            <div class="bg-white p-6 rounded-lg shadow space-y-4">
                <div>
                    <label class="text-sm text-gray-600">Nama</label>
                    <p class="font-semibold">{{ Auth::user()->name }}</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <p class="font-semibold">{{ Auth::user()->email }}</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">No. Telepon</label>
                    <p class="font-semibold">{{ Auth::user()->phone_number }}</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Departemen</label>
                    <p class="font-semibold">{{ Auth::user()->department }}</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Role</label>
                    <p class="font-semibold">{{ ucfirst(Auth::user()->role) }}</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Jam Kerja</label>
                    <p class="font-semibold">
                        {{ \Carbon\Carbon::parse(Auth::user()->work_start_time)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse(Auth::user()->work_end_time)->format('H:i') }}
                    </p>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ganti Password</h3>
                
                @if(session('password_success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('password_success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Saat Ini
                        </label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="current_password" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Baru
                        </label>
                        <input 
                            type="password" 
                            name="new_password" 
                            id="new_password" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Konfirmasi Password Baru
                        </label>
                        <input 
                            type="password" 
                            name="new_password_confirmation" 
                            id="new_password_confirmation" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition-colors"
                    >
                        Update Password
                    </button>
                </form>
            </div>

            <!-- Logout Section -->
            <div class="bg-white p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
