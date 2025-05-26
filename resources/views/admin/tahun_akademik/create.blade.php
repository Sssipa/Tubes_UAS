{{-- filepath: resources/views/admin/tahun_akademik/create.blade.php --}}
<x-app title="Tambah Tahun Akademik" section_title="Tambah Tahun Akademik Baru">
    <div class="bg-white border border-gray-300 shadow-md rounded-lg p-6">
        <form action="{{ route('admin.tahun-akademik.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Akademik <span class="text-gray-400">(Format: YYYY/YYYY)</span></label>
                <input type="text" name="tahun" id="tahun" value="{{ old('tahun') }}" placeholder="Contoh: 2024/2025"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 {{ $errors->has('tahun') ? 'border-red-500' : 'border-gray-300' }}">
                @error('tahun')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester" id="semester"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 {{ $errors->has('semester') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Semester</option>
                    <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="status_aktif" class="flex items-center">
                    <input type="checkbox" name="status_aktif" id="status_aktif" value="1" {{ old('status_aktif') ? 'checked' : '' }}
                        class="h-4 w-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
                    <span class="ml-2 text-sm text-gray-700">Jadikan Aktif? <span class="text-gray-400">(Akan menonaktifkan tahun akademik aktif lainnya)</span></span>
                </label>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.tahun-akademik.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded shadow no-underline">
                    Batal
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow no-underline">
                    Simpan Tahun Akademik
                </button>
            </div>
        </form>
    </div>
</x-app>