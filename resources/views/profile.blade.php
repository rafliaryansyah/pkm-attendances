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

                <form method="POST" action="{{ route('logout') }}" class="pt-4">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
