<x-app title="Input Nilai Mahasiswa" section_title="Pilih Kelas untuk Input Nilai">
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

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-600 dark:text-gray-400">Nama Dosen:</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $dosen->nama }} ({{ $dosen->nidn }})</p>
            </div>
            @if($tahunAkademikAktif)
            <div>
                <p class="text-gray-600 dark:text-gray-400">Periode Akademik Aktif:</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $tahunAkademikAktif->tahun }} - Semester {{ $tahunAkademikAktif->semester }}</p>
            </div>
            @else
             <div>
                <p class="text-gray-600 dark:text-gray-400">Periode Akademik Aktif:</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">-</p>
            </div>
            @endif
        </div>
    </div>
    
    @if ($pesanInfo)
        <div class="mb-4 rounded-lg bg-blue-100 px-6 py-5 text-base text-blue-700 dark:bg-blue-900 dark:text-blue-200">
            {{ $pesanInfo }}
        </div>
    @endif

    @if ($tahunAkademikAktif && $jadwalDiajar->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="text-left text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 uppercase">
                        <tr>
                            <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">No.</th>
                            <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Mata Kuliah</th>
                            <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Hari & Waktu</th>
                            <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Ruangan</th>
                            <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($jadwalDiajar as $index => $jadwal)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">
                                    {{ $jadwal->mataKuliah->kode ?? '-' }} - {{ $jadwal->mataKuliah->nama ?? 'N/A' }}
                                    ({{ $jadwal->mataKuliah->sks ?? '-' }} SKS)
                                </td>
                                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">
                                    {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                </td>
                                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $jadwal->ruangan->nama ?? '-' }}</td>
                                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">
                                    <a href="{{ route('dosen.input-nilai.showKelas', $jadwal->id_jadwal_kuliah) }}"
                                       class="text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 px-3 py-1.5 rounded-md shadow-sm no-underline">
                                        Input/Edit Nilai
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif(!$pesanInfo && $tahunAkademikAktif)
         <div class="mb-4 rounded-lg bg-yellow-100 px-6 py-5 text-base text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200">
            Tidak ada jadwal mengajar untuk Anda pada periode ini.
        </div>
    @endif
</x-app>