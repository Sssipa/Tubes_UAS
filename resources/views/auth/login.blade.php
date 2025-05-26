<x-auth-layout title="Login" section_title="Sistem Informasi Akademik" section_description="Login with your account">
    @if (session('success'))
        <div class="bg-green-50 border border-green-500 text-green-500 px-3 py-2">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('auth.authenticate') }}" method="POST" class="flex flex-col gap-4 mt-2">
        @csrf
        @error('username')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
        <div class="flex flex-col gap-2">
            <label for="username" class="text-sm font-semibold">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" class="px-3 py-2 border border-zinc-300 bg-slate-50"
            placeholder="Your username" value="{{ old('username') }}">
        </div>
        <div class="flex flex-col gap-2">
            <label for="password" class="text-sm font-semibold">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" class="px-3 py-2 border border-zinc-300 bg-slate-50"
            placeholder="Your password" value="{{ old('password') }}">
        </div>
        <div class="flex flex-col gap-2">
            <div class="flex flex-col gap-2">
                <label for="role" class="text-sm font-semibold">Login Sebagai:</label>
                <select name="role" id="role" class="px-3 py-2 border border-zinc-300 bg-slate-50">
                    <option value="">-- Silakan Pilih --</option>
                    <option value="admin">Admin</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen</option>
                </select>
                @error('role')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
            <span>Login</span>
        </button>
    </form>
</x-auth-layout>