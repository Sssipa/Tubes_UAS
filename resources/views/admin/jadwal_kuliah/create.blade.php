<x-app title="Tambah Jadwal Kuliah" section_title="Tambah Jadwal Kuliah">
    <div class="mx-auto w-full max-w-2xl bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-center text-gray-700 mb-6">Formulir Tambah Jadwal Kuliah Baru</h1>
        <form action="{{ route('jadwal-kuliah.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="mata_kuliah_id" class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                <select name="mata_kuliah_id" id="mata_kuliah_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none">
                    <option value="">Pilih Mata Kuliah</option>
                    {{-- Loop ini membutuhkan variabel $mataKuliahs dari controller --}}
                    @foreach ($mataKuliahs as $mk)
                        <option value="{{ $mk->id_mata_kuliah }}" {{ old('mata_kuliah_id') == $mk->id_mata_kuliah ? 'selected' : '' }}>
                            {{ $mk->kode }} - {{ $mk->nama }} ({{ $mk->sks }} SKS)
                        </option>
                    @endforeach
                </select>
                @error('mata_kuliah_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="dosen_id" class="block text-sm font-medium text-gray-700">Dosen Pengampu</label>
                <select name="dosen_id" id="dosen_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none">
                    <option value="">Pilih Dosen Pengampu</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->id_dosen }}" {{ old('dosen_id') == $dosen->id_dosen ? 'selected' : '' }}>
                            {{ $dosen->nidn }} - {{ $dosen->nama }}
                        </option>
                    @endforeach
                </select>
                @error('dosen_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="ruangan_id" class="block text-sm font-medium text-gray-700">Ruangan</label>
                <select name="ruangan_id" id="ruangan_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none">
                    <option value="">Pilih Ruangan</option>
                    @foreach ($ruangans as $ruangan)
                        <option value="{{ $ruangan->id_ruangan }}" {{ old('ruangan_id') == $ruangan->id_ruangan ? 'selected' : '' }}>
                            {{ $ruangan->kode }} - {{ $ruangan->nama }}
                        </option>
                    @endforeach
                </select>
                @error('ruangan_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
                <select name="hari" id="hari"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none">
                    <option value="">Pilih Hari</option>
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                        <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
                @error('hari') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none">
                    @error('jam_mulai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none">
                    @error('jam_selesai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="tahun_akademik_id" class="block text-sm font-medium text-gray-700">Tahun Akademik</label>
                <select name="tahun_akademik_id" id="tahun_akademik_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none">
                    <option value="">Pilih Tahun Akademik</option>
                    @foreach ($tahunAkademiks as $tahun)
                        <option value="{{ $tahun->id_tahun_akademik }}" {{ old('tahun_akademik_id') == $tahun->id_tahun_akademik ? 'selected' : '' }}>
                            {{ $tahun->tahun }} - Semester {{ $tahun->semester }}
                        </option>
                    @endforeach
                </select>
                @error('tahun_akademik_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end pt-4 space-x-3">
                <a href="{{ route('jadwal-kuliah.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md shadow-sm transition no-underline">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</x-app>