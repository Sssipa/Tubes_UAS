<x-app title="Kartu Rencana Studi (KRS)" section_title="Kartu Rencana Studi">
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
    @if (isset($pesanError))
         <div class="mb-4 rounded-lg bg-yellow-100 px-6 py-5 text-base text-yellow-700 dark:bg-yellow-900 dark:text-yellow-100">
            {{ $pesanError }}
        </div>
    @endif


    @if ($tahunAkademikAktif)
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Nama Mahasiswa:</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Periode KRS Aktif:</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $tahunAkademikAktif->tahun }} - Semester {{ $tahunAkademikAktif->semester }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Semester Mahasiswa Saat Ini:</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">Semester {{ $semesterMahasiswaBerjalan }}</p>
                </div>
                 @if ($pengajuanKrs)
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Status Pengajuan KRS Anda:</p>
                    <p class="font-semibold 
                        @if($pengajuanKrs->status_pengajuan == 'diajukan_mahasiswa') text-blue-600 dark:text-blue-400
                        @elseif($pengajuanKrs->status_pengajuan == 'disetujui_akademik') text-green-600 dark:text-green-400
                        @elseif($pengajuanKrs->status_pengajuan == 'ditolak_akademik') text-red-600 dark:text-red-400
                        @endif">
                        {{ ucwords(str_replace('_', ' ', $pengajuanKrs->status_pengajuan)) }}
                    </p>
                </div>
                @if($pengajuanKrs->catatan_akademik)
                <div class="md:col-span-2">
                    <p class="text-gray-600 dark:text-gray-400">Catatan dari Admin Akademik:</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 p-2 bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">{{ $pengajuanKrs->catatan_akademik }}</p>
                </div>
                @endif
                @else
                 <div>
                    <p class="text-gray-600 dark:text-gray-400">Status Pengajuan KRS Anda:</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">Belum Mengajukan</p>
                </div>
                @endif
            </div>
        </div>

        @if ($bisaMengisiKrs)
            {{-- Form Pengisian KRS --}}
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Pilih Mata Kuliah</h3>
                 <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Batas Pengambilan: <span class="font-bold">{{ $batasSks }} SKS</span></p>
                 <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Total SKS Dipilih: <span id="totalSksDipilihText" class="font-bold">{{ $sksDiambil }}</span> SKS</p>

                @if ($jadwalKuliahDitawarkan->isNotEmpty())
                    <form action="{{ route('mahasiswa.krs.store') }}" method="POST" id="formKrsMahasiswa">
                        @csrf
                        <input type="hidden" name="tahun_akademik_id" value="{{ $tahunAkademikAktif->id_tahun_akademik }}">
                        <input type="hidden" name="semester_mahasiswa_berjalan" value="{{ $semesterMahasiswaBerjalan }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead class="text-left text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 uppercase">
                                    <tr>
                                        <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 w-10">Pilih</th>
                                        <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Kode MK</th>
                                        <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Nama Mata Kuliah</th>
                                        <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">SKS</th>
                                        <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Dosen</th>
                                        <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Jadwal & Ruang</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm text-gray-700 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($jadwalKuliahDitawarkan as $jadwal)
                                        <tr>
                                            <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">
                                                <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $jadwal->mataKuliah->id_mata_kuliah }}"
                                                       class="krs-checkbox rounded border-gray-300 dark:border-gray-600 text-sky-600 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 dark:bg-gray-700 dark:focus:ring-sky-600"
                                                       data-sks="{{ $jadwal->mataKuliah->sks }}"
                                                       onchange="updateTotalSks()"
                                                       {{ in_array($jadwal->mataKuliah->id_mata_kuliah, $krsMahasiswaSaatIniIds) ? 'checked' : '' }}>
                                            </td>
                                            <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $jadwal->mataKuliah->kode }}</td>
                                            <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $jadwal->mataKuliah->nama }}</td>
                                            <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $jadwal->mataKuliah->sks }}</td>
                                            <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $jadwal->dosen->nama ?? '-' }}</td>
                                            <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-xs">
                                                {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                                <br>({{ $jadwal->ruangan->nama ?? '-' }})
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" id="submitKrsButton"
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition text-sm font-medium">
                                {{ ($pengajuanKrs && $pengajuanKrs->status_pengajuan == 'ditolak_akademik') || ($pengajuanKrs && $pengajuanKrs->status_pengajuan == 'diajukan_mahasiswa') ? 'Update & Ajukan Ulang KRS' : 'Ajukan KRS' }}
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">
                        Tidak ada mata kuliah yang ditawarkan untuk periode ini atau KRS tidak dapat diisi saat ini.
                    </p>
                @endif
            </div>
        @else
            {{-- Tampilan KRS Read-only jika sudah diajukan/disetujui --}}
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">KRS Anda untuk Periode Ini</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Total SKS Diajukan/Disetujui: <span class="font-bold">{{ $sksDiambil }} SKS</span></p>

                @if ($jadwalKuliahDitawarkan->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                             <thead class="text-left text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 uppercase">
                                <tr>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">No.</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Kode MK</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Nama Mata Kuliah</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">SKS</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Dosen</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($jadwalKuliahDitawarkan as $index => $krsItem) {{-- $jadwalKuliahDitawarkan di sini berisi KRS yg sudah diambil --}}
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $krsItem->mataKuliah->kode ?? 'N/A'}}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $krsItem->mataKuliah->nama ?? 'Data MK Hilang' }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $krsItem->mataKuliah->sks ?? '-'}}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $krsItem->mataKuliah->dosen->nama ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                     <p class="text-center text-gray-500 dark:text-gray-400 py-4">
                        Detail KRS tidak ditemukan.
                    </p>
                @endif
            </div>
        @endif

    @endif

    <script>
        const batasSks = {{ $batasSks ?? 24 }};
        const totalSksDipilihTextElement = document.getElementById('totalSksDipilihText');
        const submitKrsButton = document.getElementById('submitKrsButton');

        function updateTotalSks() {
            if (!totalSksDipilihTextElement) return;

            let currentTotalSks = 0;
            document.querySelectorAll('.krs-checkbox:checked').forEach(function(checkbox) {
                currentTotalSks += parseInt(checkbox.dataset.sks);
            });
            totalSksDipilihTextElement.innerText = currentTotalSks;

            if (currentTotalSks > batasSks) {
                totalSksDipilihTextElement.classList.add('text-red-500');
                totalSksDipilihTextElement.classList.remove('text-green-500');
                if(submitKrsButton) submitKrsButton.disabled = true;
                 // Tambahkan pesan peringatan jika perlu
            } else if (currentTotalSks === 0 && document.querySelectorAll('.krs-checkbox:checked').length > 0) {
                // Ini kondisi aneh, SKS 0 tapi ada yg dipilih (misal MK 0 SKS)
                totalSksDipilihTextElement.classList.remove('text-red-500', 'text-green-500');
                if(submitKrsButton) submitKrsButton.disabled = false;
            }
            else {
                totalSksDipilihTextElement.classList.remove('text-red-500');
                totalSksDipilihTextElement.classList.add('text-green-500'); // Atau warna default
                if(submitKrsButton) submitKrsButton.disabled = false;
            }
        }

        // Panggil saat halaman dimuat untuk inisialisasi
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.krs-checkbox')) { // Hanya jalankan jika ada checkbox
                 updateTotalSks();
            }
        });
    </script>
</x-app>
