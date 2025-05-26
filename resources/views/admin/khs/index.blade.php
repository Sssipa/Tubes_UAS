<x-app title="Manajemen KHS Mahasiswa" section_title="Input/Edit Kartu Hasil Studi">
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 px-6 py-5 text-base text-green-700 dark:bg-green-900 dark:text-green-100">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 dark:bg-red-900 dark:text-red-100">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form Seleksi --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Pilih Mahasiswa dan Periode Studi</h3>
        <form action="{{ route('admin.khs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="mahasiswa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mahasiswa</label>
                <select name="mahasiswa_id" id="mahasiswa_id" required
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                               focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                               dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach ($mahasiswas as $mahasiswa)
                        <option value="{{ $mahasiswa->id_mahasiswa }}" {{ old('mahasiswa_id', $selectedMahasiswa ? $selectedMahasiswa->id_mahasiswa : '') == $mahasiswa->id_mahasiswa ? 'selected' : '' }}>
                            {{ $mahasiswa->nim }} - {{ $mahasiswa->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tahun_akademik_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Akademik</label>
                <select name="tahun_akademik_id" id="tahun_akademik_id" required
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                               focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                               dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
                    <option value="">-- Pilih Tahun Akademik --</option>
                    @foreach ($tahunAkademiks as $ta)
                        <option value="{{ $ta->id_tahun_akademik }}" {{ old('tahun_akademik_id', $selectedTahunAkademik ? $selectedTahunAkademik->id_tahun_akademik : '') == $ta->id_tahun_akademik ? 'selected' : '' }}>
                            {{ $ta->tahun }} - {{ $ta->semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="semester_mahasiswa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Semester Ke- Mahasiswa</label>
                <input type="number" name="semester_mahasiswa" id="semester_mahasiswa" required min="1" max="14"
                       value="{{ old('semester_mahasiswa', $inputSemesterMahasiswa ?? '') }}"
                       placeholder="Cth: 3"
                       class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 shadow-sm 
                              focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                              dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600">
            </div>
            <div class="md:col-span-3 flex justify-end mt-2">
                <button type="submit"
                        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-center no-underline rounded-md shadow-sm transition text-sm font-medium">
                    Tampilkan Mata Kuliah
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Input Nilai (Muncul jika data sudah dipilih) --}}
    @if ($selectedMahasiswa && $selectedTahunAkademik && $inputSemesterMahasiswa)
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">
                Input Nilai untuk: {{ $selectedMahasiswa->nama }} ({{ $selectedMahasiswa->nim }})
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Tahun Akademik: {{ $selectedTahunAkademik->tahun }} - {{ $selectedTahunAkademik->semester }} | Semester Mahasiswa Ke-: {{ $inputSemesterMahasiswa }}
            </p>

            @if ($krsDetails->isNotEmpty())
                <form action="{{ route('admin.khs.storeOrUpdate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mahasiswa_id" value="{{ $selectedMahasiswa->id_mahasiswa }}">
                    <input type="hidden" name="tahun_akademik_id" value="{{ $selectedTahunAkademik->id_tahun_akademik }}">
                    <input type="hidden" name="semester_mahasiswa" value="{{ $inputSemesterMahasiswa }}">

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">No.</th>
                                    <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Kode MK</th>
                                    <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Nama Mata Kuliah</th>
                                    <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">SKS</th>
                                    <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center" style="width: 100px;">Nilai Huruf</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($krsDetails as $index => $krs)
                                    @php
                                        $khsEntry = $khsDetails->get($krs->mata_kuliah_id);
                                        $nilaiSudahAda = $khsEntry ? $khsEntry->nilai : '';
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $krs->mataKuliah->kode }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $krs->mataKuliah->nama }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $krs->mataKuliah->sks }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">
                                            <input type="hidden" name="nilai[{{ $krs->mata_kuliah_id }}][mata_kuliah_id]" value="{{ $krs->mata_kuliah_id }}">
                                            <select name="nilai[{{ $krs->mata_kuliah_id }}][nilai]"
                                                    class="block w-full border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 shadow-sm 
                                                        focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                                                        dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600 text-xs">
                                                <option value="" {{ $nilaiSudahAda == '' ? 'selected' : '' }}>-</option>
                                                <option value="A+" {{ $nilaiSudahAda == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A" {{ $nilaiSudahAda == 'A' ? 'selected' : '' }}>A</option>
                                                <option value="A-" {{ $nilaiSudahAda == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ $nilaiSudahAda == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B" {{ $nilaiSudahAda == 'B' ? 'selected' : '' }}>B</option>
                                                <option value="B-" {{ $nilaiSudahAda == 'B-' ? 'selected' : '' }}>B=-</option>
                                                <option value="C" {{ $nilaiSudahAda == 'C' ? 'selected' : '' }}>C</option>
                                                <option value="D" {{ $nilaiSudahAda == 'D' ? 'selected' : '' }}>C</option>
                                                <option value="E" {{ $nilaiSudahAda == 'E' ? 'selected' : '' }}>D</option>
                                                <option value="T" {{ $nilaiSudahAda == 'T' ? 'selected' : '' }}>T (Tunda)</option>
                                            </select>
                                            @error('nilai.'.$krs->mata_kuliah_id.'.nilai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-center">
                            Simpan Nilai KHS
                        </button>
                    </div>
                </form>
            @elseif(request()->filled('mahasiswa_id'))
                <p class="text-center text-gray-500 dark:text-gray-400 py-4">
                    Tidak ada data KRS (mata kuliah yang diambil) untuk mahasiswa, tahun akademik, dan semester yang dipilih.
                </p>
            @endif
        </div>
    @endif
</x-app>
