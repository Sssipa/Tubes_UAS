{{-- filepath: resources/views/admin/ruangan/create.blade.php --}}
<x-app title="Tambah Ruangan" section_title="Tambah Ruangan">
    <div class="mx-auto w-full max-w-2xl bg-white p-6 rounded shadow-md">
        <h1 class="text-2xl font-semibold text-center mb-6">Tambah Data Ruangan</h1>
        <form action="{{ route('admin.ruangan.store') }}" method="POST" class="space-y-8">
            @csrf

            <div>
                <label for="kode" class="block text-sm font-medium text-gray-700">Kode Ruangan</label>
                <input type="text" name="kode" id="kode" value="{{ old('kode') }}"
                    placeholder="Masukkan Kode Ruangan"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('kode') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Ruangan</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                    placeholder="Masukkan Nama Ruangan"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('nama') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}"
                    placeholder="Masukkan Kapasitas Ruangan"
                    min="1"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                @error('kapasitas') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('admin.ruangan.index') }}"
                    class="mr-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded shadow transition no-underline">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow transition">Simpan</button>
            </div>
        </form>
    </div>
</x-app>