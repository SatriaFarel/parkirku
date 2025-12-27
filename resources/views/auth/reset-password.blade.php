<x-guest-layout>
    <div class="max-w-md mx-auto mt-12 px-6 py-6 bg-[#1e293b] shadow-xl rounded-lg ">
        <h2 class="font-semibold mb-6 text-white text-center text-xl">
            Reset Password
        </h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Token Reset Password --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-300">Email</label>
                <input type="email" name="email" value="{{ request('email') }}" class="w-full bg-[#0f172a] border border-gray-600 rounded-lg p-2.5 text-gray-100 placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('email')
                    <p style="color:#f87171; font-size:14px; margin-top:4px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password Baru --}}
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-300">Password Baru</label>
                <input type="password" name="password" class="w-full bg-[#0f172a] border border-gray-600 rounded-lg p-2.5 text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('password')
                    <p style="color:#f87171; font-size:14px; margin-top:4px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-6">
                <label class="block mb-1 font-medium text-gray-300">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full bg-[#0f172a] border border-gray-600 rounded-lg p-2.5 text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-lg w-full transition">
                Reset Password
            </button>
        </form>
    </div>
</x-guest-layout>