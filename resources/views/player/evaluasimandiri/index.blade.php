@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <a href="/" class="btn btn-secondary mb-3">
        &larr; Kembali
    </a>
    @include('partial.player-data')
    <h2 class="mb-4 mt-5 text-center">Evaluasi Mandiri</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
   
    <div class="row">
       
        <div class="col-md-12 text-end mb-3 mt-5">
            <a href="{{ route('evaluasimandiri.create') }}" class="btn btn-success">Isi Evaluasi Mandiri</a>
        </div>
    </div>

    @if ($evaluasiMandiri->isEmpty())
        <p class="text-center text-muted">Belum ada evaluasi mandiri yang dilakukan.</p>
    @else
        @foreach ($evaluasiMandiri as $evaluasi)
            <div class="row mb-3">
                <div class="col-3">
                    <strong>{{ $evaluasi->tanggal }}</strong>
                </div>
                <div class="col-9">
                    <p><strong>Positif Attacking:</strong> {{ $evaluasi->positif_attacking }}</p>
                    <p><strong>Positif Negatif:</strong> {{ $evaluasi->negatif_attacking }}</p>
                    <p><strong>Defending Positif:</strong> {{ $evaluasi->positif_defending }}</p>
                    <p><strong>Defending Negatif:</strong> {{ $evaluasi->negatif_defending }}</p>
                    <p><strong>Target Pengembangan:</strong> 
                        <ul>
                            <li>{{ $evaluasi->target_pengembangan_1 }}</li>
                            <li>{{ $evaluasi->target_pengembangan_2 }}</li>
                            <li>{{ $evaluasi->target_pengembangan_3 }}</li>
                        </ul>
                    </p>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
