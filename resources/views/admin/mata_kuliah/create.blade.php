{{-- filepath: resources/views/admin/mata_kuliah/create.blade.php --}}
<x-app title="Tambah Mata Kuliah" section_title="Tambah Mata Kuliah">
    <div class="mx-auto w-full max-w-2xl bg-white p-6 rounded shadow-md">
        <h1 class="text-2xl font-semibold text-center mb-6">Tambah Data Mata Kuliah</h1>
        <form action="{{ route('admin.mata-kuliah.store') }}" method="POST" class="space-y-8">
            @csrf

            <div>
                <label for="kode" class="block text-sm font-medium text-gray-700">Kode Mata Kuliah</label>
                <input type="text" name="kode" id="kode" value="{{ old('kode') }}"
                    placeholder="Masukkan Kode Mata Kuliah"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('kode') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Mata Kuliah</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                    placeholder="Masukkan Nama Mata Kuliah"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('nama') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="sks" class="block text-sm font-medium text-gray-700">SKS</label>
                <input type="number" name="sks" id="sks" value="{{ old('sks') }}"
                    placeholder="Masukkan Jumlah SKS"
                    min="1" max="6"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('sks') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="dosen_id" class="block text-sm font-medium text-gray-700">Dosen Pengampu</label>
                <select name="dosen_id" id="dosen_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="">Pilih Dosen</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->id_dosen }}" {{ old('dosen_id') == $dosen->id_dosen ? 'selected' : '' }}>
                            {{ $dosen->nama }}
                        </option>
                    @endforeach
                </select>
                @error('dosen_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('admin.mata-kuliah.index') }}"
                    class="mr-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded shadow transition no-underline">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow transition">Simpan</button>
            </div>
        </form>
    </div>
</x-app>