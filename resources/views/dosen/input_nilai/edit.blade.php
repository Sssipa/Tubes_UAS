<x-app title="Input Nilai {{ $jadwalKuliah->mataKuliah->nama ?? '' }}" section_title="Input Nilai Mahasiswa">
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
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 dark:bg-red-900 dark:text-red-100">
            <p class="font-bold">Terdapat kesalahan validasi:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Informasi Kelas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-2">
            <p><strong class="text-gray-600 dark:text-gray-400">Mata Kuliah:</strong> {{ $jadwalKuliah->mataKuliah->kode ?? '-' }} - {{ $jadwalKuliah->mataKuliah->nama ?? 'N/A' }} ({{ $jadwalKuliah->mataKuliah->sks ?? '-' }} SKS)</p>
            <p><strong class="text-gray-600 dark:text-gray-400">Dosen Pengampu:</strong> {{ $dosen->nama }}</p>
            <p><strong class="text-gray-600 dark:text-gray-400">Tahun Akademik:</strong> {{ $jadwalKuliah->tahunAkademik->tahun ?? '-' }} - {{ $jadwalKuliah->tahunAkademik->semester ?? '-' }}</p>
            <p><strong class="text-gray-600 dark:text-gray-400">Jadwal:</strong> {{ $jadwalKuliah->hari }}, {{ \Carbon\Carbon::parse($jadwalKuliah->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwalKuliah->jam_selesai)->format('H:i') }} @ {{ $jadwalKuliah->ruangan->nama ?? '-'}}</p>
        </div>
    </div>

    @if (!empty($mahasiswaDataForGrading))
        <form action="{{ route('dosen.input-nilai.store', $jadwalKuliah->id_jadwal_kuliah) }}" method="POST">
            @csrf
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="text-left text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 uppercase">
                            <tr>
                                <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">No.</th>
                                <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">NIM</th>
                                <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Nama Mahasiswa</th>
                                <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">Semester Mhs.</th>
                                <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center" style="width: 120px;">Input Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($mahasiswaDataForGrading as $index => $data)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 whitespace-nowrap">{{ $data['mahasiswa']->nim }}</td>
                                    <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 whitespace-nowrap">{{ $data['mahasiswa']->user->name ?? $data['mahasiswa']->nama }}</td>
                                    <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $data['semester_mahasiswa'] }}</td>
                                    <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">
                                        <input type="hidden" name="nilai_input[{{ $data['mahasiswa']->id_mahasiswa }}][mahasiswa_id]" value="{{ $data['mahasiswa']->id_mahasiswa }}">
                                        <input type="hidden" name="nilai_input[{{ $data['mahasiswa']->id_mahasiswa }}][semester_mahasiswa]" value="{{ $data['semester_mahasiswa'] }}">
                                        <select name="nilai_input[{{ $data['mahasiswa']->id_mahasiswa }}][nilai]"
                                                class="block w-full border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 shadow-sm 
                                                       focus:ring focus:ring-sky-300 focus:border-sky-500 focus:outline-none 
                                                       dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-sky-600 dark:focus:border-sky-600 text-xs">
                                            <option value="" {{ is_null($data['nilai_existing']) ? 'selected' : '' }}>- Kosong -</option>
                                            @foreach ($skalaNilai as $nilaiOpsi)
                                                <option value="{{ $nilaiOpsi }}" {{ $data['nilai_existing'] === $nilaiOpsi ? 'selected' : '' }}>
                                                    {{ $nilaiOpsi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('dosen.input-nilai.index') }}" class="text-sky-600 hover:text-sky-800 dark:text-sky-400 dark:hover:text-sky-300 hover:underline text-sm">
                    &larr; Kembali ke Daftar Kelas
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition text-sm font-medium">
                    Simpan Semua Nilai
                </button>
            </div>
        </form>
    @else
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg p-6">
            <p class="text-center text-gray-500 dark:text-gray-400 py-4">
                Tidak ada mahasiswa yang terdaftar (KRS disetujui) pada kelas ini atau data tidak ditemukan.
            </p>
             <div class="mt-6">
                <a href="{{ route('dosen.input-nilai.index') }}" class="text-sky-600 hover:text-sky-800 dark:text-sky-400 dark:hover:text-sky-300 hover:underline text-sm">
                    &larr; Kembali ke Daftar Kelas
                </a>
            </div>
        </div>
    @endif
</x-app>