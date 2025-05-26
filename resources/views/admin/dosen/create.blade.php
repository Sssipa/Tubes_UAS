<x-app title="Tambah Dosen" section_title="Tambah Dosen">
        <div class="mx-auto w-full max-w-2xl bg-white p-6 rounded shadow-md">
            <h1 class="text-2xl font-semibold text-center mb-6">Tambah Data Dosen</h1>
            <form action="{{ route('admin.dosen.store') }}" method="POST" class="space-y-10">
                @csrf

                <div>
                    <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN</label>
                    <input type="text" name="nidn" id="nidn" value="{{ old('nidn') }}"
                        placeholder="Masukkan NIDN"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                    @error('nidn') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                        placeholder="Masukkan Nama Dosen"
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

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.dosen.index') }}"
                        class="mr-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded shadow transition no-underline">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow transition">Simpan</button>
                </div>
            </form>
        </div>
</x-app>
