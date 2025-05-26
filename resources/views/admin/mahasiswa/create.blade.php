<x-app title="Tambah Mahasiswa" section_title="Tambah Mahasiswa">
    <div class="mx-auto w-full max-w-2xl bg-white p-6 rounded shadow-md">
        <h1 class="text-2xl font-semibold text-center mb-6">Tambah Data Mahasiswa</h1>
        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-8">
            @csrf

            <div>
                <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                <input type="text" name="nim" id="nim" value="{{ old('nim') }}"
                    placeholder="Masukkan NIM"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('nim') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                    placeholder="Masukkan Nama Mahasiswa"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('nama') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}"
                    placeholder="Masukkan Alamat"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('alamat') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    placeholder="Masukkan Email"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}"
                    placeholder="Masukkan No. Telepon"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('telepon') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tahun_akademik_id" class="block text-sm font-medium text-gray-700">Tahun Akademik</label>
                <select name="tahun_akademik_id" id="tahun_akademik_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="">Pilih Tahun Akademik</option>
                    @foreach ($tahunAkademiks as $tahun)
                        <option value="{{ $tahun->id_tahun_akademik }}" {{ old('tahun_akademik_id') == $tahun->id_tahun_akademik ? 'selected' : '' }}>
                            {{ $tahun->tahun }}
                        </option>
                    @endforeach
                </select>
                @error('tahun_akademik_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('admin.mahasiswa.index') }}"
                    class="mr-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded shadow transition no-underline">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow transition">Simpan</button>
            </div>
        </form>
    </div>
</x-app>