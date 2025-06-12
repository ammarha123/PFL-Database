@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Evaluasi Mandiri - {{ $player->user->name }}</h4>
    <p class="text-muted">Tim: {{ $player->teams->first()->name ?? '-' }}</p>

    <div class="row">
        @forelse ($player->user->evaluasiMandiri as $i => $evaluasi)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm p-3 h-100">
                    <h6 class="fw-bold mb-2">Evaluasi ke-{{ $i + 1 }}</h6>
                    <p class="mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($evaluasi->tanggal)->format('d M Y') }}</p>
                    <p class="mb-1"><strong>Target:</strong></p>
                    <ul class="mb-2">
                        <li>{{ $evaluasi->target_pengembangan_1 ?? '-' }}</li>
                        <li>{{ $evaluasi->target_pengembangan_2 ?? '-' }}</li>
                        <li>{{ $evaluasi->target_pengembangan_3 ?? '-' }}</li>
                    </ul>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEvaluasi{{ $evaluasi->id }}">Lihat Detail</button>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalEvaluasi{{ $evaluasi->id }}" tabindex="-1" aria-labelledby="modalEvaluasiLabel{{ $evaluasi->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEvaluasiLabel{{ $evaluasi->id }}">Detail Evaluasi ({{ \Carbon\Carbon::parse($evaluasi->tanggal)->format('d M Y') }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Attack (+):</strong> {{ $evaluasi->positif_attacking }}</p>
                            <p><strong>Attack (-):</strong> {{ $evaluasi->negatif_attacking }}</p>
                            <p><strong>Defend (+):</strong> {{ $evaluasi->positif_defending }}</p>
                            <p><strong>Defend (-):</strong> {{ $evaluasi->negatif_defending }}</p>
                            <hr>
                            <p><strong>Target Pengembangan:</strong></p>
                            <ol>
                                <li>{{ $evaluasi->target_pengembangan_1 ?? '-' }}</li>
                                <li>{{ $evaluasi->target_pengembangan_2 ?? '-' }}</li>
                                <li>{{ $evaluasi->target_pengembangan_3 ?? '-' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">Belum ada data evaluasi mandiri untuk pemain ini.</div>
            </div>
        @endforelse
    </div>

    <a href="{{ route('evaluasi_mandiri_admin.index') }}" class="btn btn-secondary mt-3">← Kembali</a>
</div>
@endsection
