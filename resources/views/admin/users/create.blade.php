<x-app title="Tambah Akun Pengguna" section_title="Formulir Tambah Akun Pengguna Baru">
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        {{-- Judul Form bisa dihilangkan jika section_title sudah cukup jelas --}}
        {{-- <h1 class="text-xl font-semibold text-gray-700 dark:text-gray-200 text-center mb-6">Tambah Akun Baru</h1> --}}

        <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    placeholder="Masukkan nama lengkap pengguna"
                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                            focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                            dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required
                    placeholder="Masukkan username (unik)"
                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                            focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                            dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" name="password" id="password" required
                    placeholder="Minimal 8 karakter"
                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                            focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                            dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    placeholder="Ulangi password"
                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                            focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                            dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                <select name="role" id="role" required
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                            focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                            dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Pilihan untuk Mahasiswa --}}
            <div id="mahasiswa_select_div" class="hidden">
                <label for="mahasiswa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Mahasiswa Terkait</label>
                <select name="mahasiswa_id" id="mahasiswa_id"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                            focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                            dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
                    <option value="">Pilih Mahasiswa</option>
                    {{-- Loop ini membutuhkan variabel $mahasiswas dari controller --}}
                    @foreach ($mahasiswas as $mahasiswa)
                        <option value="{{ $mahasiswa->id_mahasiswa }}" {{ old('mahasiswa_id') == $mahasiswa->id_mahasiswa ? 'selected' : '' }}>
                            {{ $mahasiswa->nim }} - {{ $mahasiswa->nama }}
                        </option>
                    @endforeach
                </select>
                @error('mahasiswa_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hanya menampilkan mahasiswa yang belum memiliki akun.</p>
            </div>

            {{-- Pilihan untuk Dosen --}}
            <div id="dosen_select_div" class="hidden">
                <label for="dosen_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Dosen Terkait</label>
                <select name="dosen_id" id="dosen_id"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                            focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                            dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
                    <option value="">Pilih Dosen</option>
                    {{-- Loop ini membutuhkan variabel $dosens dari controller --}}
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->id_dosen }}" {{ old('dosen_id') == $dosen->id_dosen ? 'selected' : '' }}>
                            {{ $dosen->nidn }} - {{ $dosen->nama }}
                        </option>
                    @endforeach
                </select>
                @error('dosen_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hanya menampilkan dosen yang belum memiliki akun.</p>
            </div>

            <div class="flex items-center justify-end pt-3 space-x-3 border-t border-gray-200 dark:border-gray-700 mt-8 pt-6">
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 rounded-md shadow-sm transition no-underline text-sm font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition text-sm font-medium">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const mahasiswaSelectDiv = document.getElementById('mahasiswa_select_div');
            const dosenSelectDiv = document.getElementById('dosen_select_div');
            const mahasiswaIdSelect = document.getElementById('mahasiswa_id');
            const dosenIdSelect = document.getElementById('dosen_id');

            function toggleConditionalFields() {
                const selectedRole = roleSelect.value;
                mahasiswaSelectDiv.classList.add('hidden');
                mahasiswaIdSelect.required = false; // Nonaktifkan required secara default
                mahasiswaIdSelect.value = ''; // Kosongkan pilihan

                dosenSelectDiv.classList.add('hidden');
                dosenIdSelect.required = false; // Nonaktifkan required secara default
                dosenIdSelect.value = ''; // Kosongkan pilihan

                if (selectedRole === 'mahasiswa') {
                    mahasiswaSelectDiv.classList.remove('hidden');
                    mahasiswaIdSelect.required = true; // Aktifkan required jika role mahasiswa
                } else if (selectedRole === 'dosen') {
                    dosenSelectDiv.classList.remove('hidden');
                    dosenIdSelect.required = true; // Aktifkan required jika role dosen
                }
            }

            roleSelect.addEventListener('change', toggleConditionalFields);
            // Panggil saat load untuk menangani old input atau kondisi awal
            toggleConditionalFields();
        });
    </script>
</x-app>