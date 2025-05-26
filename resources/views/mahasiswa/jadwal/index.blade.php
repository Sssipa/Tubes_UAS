<x-app title="Jadwal Kuliah Saya" section_title="Jadwal Kuliah Semester Ini">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-600 dark:text-gray-400">Nama Mahasiswa:</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</p>
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

    @if ($tahunAkademikAktif && $jadwalKuliahMahasiswa->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="text-left text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 uppercase border-b border-gray-300 dark:border-gray-600">
                    <tr>
                        <th class="px-4 py-2">No.</th>
                        <th class="px-4 py-2">Hari</th>
                        <th class="px-4 py-2">Waktu</th>
                        <th class="px-4 py-2">Kode MK</th>
                        <th class="px-4 py-2">Nama Mata Kuliah</th>
                        <th class="px-4 py-2 text-center">SKS</th>
                        <th class="px-4 py-2">Dosen Pengampu</th>
                        <th class="px-4 py-2">Ruangan</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($jadwalKuliahMahasiswa as $index => $jadwal)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-2 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $jadwal->hari }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $jadwal->mataKuliah->kode ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $jadwal->mataKuliah->nama ?? 'Mata Kuliah Tidak Ditemukan' }}</td>
                        <td class="px-4 py-2 text-center whitespace-nowrap">{{ $jadwal->mataKuliah->sks ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $jadwal->dosen->nama ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $jadwal->ruangan->nama ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(!$pesanInfo && $tahunAkademikAktif) {{-- Ditambahkan kondisi !$pesanInfo agar pesan ini tidak tumpang tindih dengan $pesanInfo spesifik dari controller --}}
    <div class="mb-4 rounded-lg bg-yellow-100 px-6 py-5 text-base text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200">
    Tidak ada jadwal kuliah yang dapat ditampilkan untuk periode ini atau KRS Anda belum disetujui.
    </div>
    @endif
</x-app>