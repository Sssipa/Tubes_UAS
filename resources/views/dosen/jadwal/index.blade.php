{{-- filepath: resources/views/dosen/jadwal/index.blade.php --}}
<x-app title="Jadwal Mengajar" section_title="Jadwal Mengajar">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Jadwal Mengajar Saya</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dosen.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Jadwal Mengajar</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-calendar-alt me-1"></i>
                Informasi Dosen dan Periode Aktif
            </div>
            <div class="card-body">
                <p><strong>Nama Dosen:</strong> {{ $dosen->nama }} (NIDN: {{ $dosen->nidn }})</p>
                @if($tahunAkademikAktif)
                    <p><strong>Tahun Akademik Aktif:</strong> {{ $tahunAkademikAktif->tahun }} - Semester {{ $tahunAkademikAktif->semester }}</p>
                @else
                    <div class="alert alert-warning">Tidak ada tahun akademik yang sedang aktif.</div>
                @endif
            </div>
        </div>

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Daftar Jadwal Mengajar
            </div>
            <div class="card-body">
                @if($tahunAkademikAktif && $jadwalMengajar->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTableJadwalDosen">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Kode MK</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Ruangan</th>
                                    <th>Tahun Akademik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwalMengajar as $index => $jadwal)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $jadwal->hari }}</td>
                                        <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                                        <td>{{ $jadwal->mataKuliah->kode ?? '-' }}</td>
                                        <td>{{ $jadwal->mataKuliah->nama ?? '-' }}</td>
                                        <td class="text-center">{{ $jadwal->mataKuliah->sks ?? '-' }}</td>
                                        <td>{{ $jadwal->ruangan->nama ?? '-' }}</td>
                                        <td>
                                            {{ $jadwal->tahunAkademik->tahun ?? '-' }}
                                            @if(isset($jadwal->tahunAkademik->semester))
                                                - Semester {{ $jadwal->tahunAkademik->semester }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif($tahunAkademikAktif)
                    <div class="alert alert-info">
                        Anda tidak memiliki jadwal mengajar pada periode {{ $tahunAkademikAktif->tahun }} Semester {{ $tahunAkademikAktif->semester }}.
                    </div>
                @else
                    <div class="alert alert-info">
                        Jadwal tidak dapat ditampilkan karena tidak ada tahun akademik yang aktif.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app>