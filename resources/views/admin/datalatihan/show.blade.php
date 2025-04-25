@extends('layout.admin')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary">Dashboard</a></li>
                <li class="breadcrumb-item text-secondary"><a href="{{ route('datalatihan.index') }}" class="text-decoration-none text-secondary">Data Latihan</a></li>
                <li class="breadcrumb-item text-success" aria-current="page">Detail</li>
            </ol>
        </nav>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Detail Data Latihan</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">Tanggal Latihan</h5>
                <p class="card-text">{{ $datalatihan->tanggal }}</p>

                <h5 class="card-title">Nama File Latihan</h5>
                <p class="card-text">
                    <a href="{{ asset('storage/' . $datalatihan->latihan_file_path) }}" target="_blank"
                        class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-download me-2"></i>{{ basename($datalatihan->latihan_file_path) }}
                    </a>
                </p>

                <h5 class="card-title">Video Latihan</h5>
                @if ($videos->isNotEmpty())
                    <p class="card-text">
                        @foreach ($videos as $video)
                            <a href="{{ $video->link_video }}" target="_blank" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-video me-2"></i>Tonton Video
                            </a>
                        @endforeach
                    </p>
                    <ul class="list-group">

                    </ul>
                @else
                    <p class="text-muted">Belum ada video yang tersedia.</p>
                @endif

                <h5 class="card-title">Tim</h5>
                @if ($datalatihan->teams->isEmpty())
                    <p class="text-muted">Tidak ada tim yang dipilih.</p>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($datalatihan->teams as $team)
                            <span class="badge bg-success" style="font-size: 0.9rem; padding: 0.5em 0.75em;">{{ $team->name }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Player Attendance -->
                <h5 class="card-title mt-3">Kehadiran Pemain</h5>
                @if ($datalatihan->players->isEmpty())
                    <p class="card-text">Tidak ada pemain yang dipilih.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Pemain</th>
                                <th>Status Kehadiran</th>
                                <th>Alasan (Jika Tidak Hadir)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datalatihan->players as $player)
                                <tr>
                                    <td>{{ $player->user->name }}</td>
                                    <td>
                                        {{ $player->pivot->status == 'Hadir' ? '✅ Hadir' : '❌ Tidak Hadir' }}
                                    </td>
                                    <td>
                                        {{ $player->pivot->status == 'Tidak Hadir' ? $player->pivot->reason : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
                    <a href="{{ route('datalatihan.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
