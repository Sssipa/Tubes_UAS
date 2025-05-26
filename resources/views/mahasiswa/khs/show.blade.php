<x-app title="Detail KHS Semester {{ $semester }}" section_title="Detail Kartu Hasil Studi Semester {{ $semester }}">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <p class="text-gray-600 dark:text-gray-400">Nama Mahasiswa:</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</p>
            </div>
            <div>
                <p class="text-gray-600 dark:text-gray-400">Semester Ke-:</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $semester }}</p>
            </div>
        </div>
        <hr class="dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mt-4">
            <div>
                <p class="text-gray-600 dark:text-gray-400">Total SKS Diambil:</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $totalSksSemesterIni }}</p>
            </div>
            <div>
                <p class="text-gray-600 dark:text-gray-400">Total Mutu (SKS x Bobot):</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ number_format($totalMutuSemesterIni, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-600 dark:text-gray-400">Indeks Prestasi Semester (IPS):</p>
                <p class="font-bold text-xl text-sky-600 dark:text-sky-400">{{ number_format($ips, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="text-left text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 uppercase">
                    <tr>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">No.</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Kode MK</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Nama Mata Kuliah</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">SKS</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">Nilai</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">Bobot</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">Mutu (SKS*Bobot)</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($detailNilaiSemester as $index => $nilai)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $nilai['kode_mk'] }}</td>
                            <td class="px-4 py-3">{{ $nilai['nama_mk'] }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $nilai['sks'] }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap font-semibold">{{ $nilai['nilai_huruf'] ?: '-' }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $nilai['bobot'] !== null ? number_format($nilai['bobot'], 2) : '-' }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $nilai['mutu'] !== null ? number_format($nilai['mutu'], 2) : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Tidak ada data nilai untuk semester ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">
        <a href="{{ route('mahasiswa.khs.index') }}"
           class="text-sky-600 hover:text-sky-800 dark:text-sky-400 dark:hover:text-sky-300 hover:underline text-sm">
            &larr; Kembali ke Ringkasan KHS
        </a>
    </div>
</x-app>