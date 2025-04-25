@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Detail Data Video</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Kategori Video</h5>
            <p class="card-text">{{ $datavideo->video_category }}</p>

            <h5 class="card-title">Tanggal Upload</h5>
            <p class="card-text">{{ $datavideo->created_at->format('d M Y') }}</p>

            <h5 class="card-title">Details</h5>
            <p class="card-text">
                @if ($datavideo->video_category === 'Full Match')
                <div class="tanggal">
                    Tanggal Pertandingan: {{ $datavideo->match->tanggal ?? '-' }}
                </div>
                <div class="tanggal">
                    Tim: {{ $datavideo->match->home_team ?? '-' }} vs  {{ $datavideo->match->away_team ?? '-' }}
                </div>
                   
                @elseif ($datavideo->video_category === 'Latihan')
                    Tanggal Latihan: {{ $datavideo->latihan->tanggal ?? '-' }}
                @else
                    -
                @endif
            </p>

            <h5 class="card-title">Link Video</h5>
            <p class="card-text">
                <a href="{{ $datavideo->link_video }}" target="_blank">
                    {{ $datavideo->link_video }}
                </a>
            </p>

            <a href="{{ route('datavideo.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
