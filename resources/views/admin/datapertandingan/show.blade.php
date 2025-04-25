@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Detail Pertandingan</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Tanggal Pertandingan</h5>
            <p class="card-text">{{ $datapertandingan->tanggal }}</p>

            <h5 class="card-title">Tim Tuan Rumah</h5>
            <p class="card-text">{{ $datapertandingan->home_team }}</p>

            <h5 class="card-title">Tim Tamu</h5>
            <p class="card-text">{{ $datapertandingan->away_team }}</p>

            <h5 class="card-title">Skor Pertandingan</h5>
            <p class="card-text">{{ $datapertandingan->home_score }} - {{ $datapertandingan->away_score }}</p>

            <h5 class="card-title">Lokasi Pertandingan</h5>
            <p class="card-text">{{ $datapertandingan->location }}</p>

            <!-- Goal Scorers -->
            <h5 class="mt-4">Pencetak Gol</h5>
            @if ($datapertandingan->goals->isNotEmpty())
                <ul class="list-group">
                    @foreach ($datapertandingan->goals as $goal)
                        <li class="list-group-item">
                            {{ $goal->player }} - Menit ke-{{ $goal->minute }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Tidak ada pencetak gol.</p>
            @endif

            <!-- Yellow Cards -->
            <h5 class="mt-4">Kartu Kuning</h5>
            @if ($datapertandingan->yellowCards->isNotEmpty())
                <ul class="list-group">
                    @foreach ($datapertandingan->yellowCards as $yellow)
                        <li class="list-group-item">
                            {{ $yellow->player }} - Menit ke-{{ $yellow->minute }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Tidak ada kartu kuning.</p>
            @endif

            <!-- Red Cards -->
            <h5 class="mt-4">Kartu Merah</h5>
            @if ($datapertandingan->redCards->isNotEmpty())
                <ul class="list-group">
                    @foreach ($datapertandingan->redCards as $red)
                        <li class="list-group-item">
                            {{ $red->player }} - Menit ke-{{ $red->minute }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Tidak ada kartu merah.</p>
            @endif

            <!-- Match Notes -->
            <h5 class="mt-4">Catatan Pertandingan</h5>
            <p>{{ $datapertandingan->notes ?? 'Tidak ada catatan tambahan.' }}</p>

           <!-- Match Videos -->
            <h5 class="mt-4">Video Pertandingan</h5>
            @if ($videos->isNotEmpty())
                <ul class="list-group">
                    @foreach ($videos as $video)
                        <li class="list-group-item">
                            <a href="{{ $video->link_video }}" target="_blank">Tonton Video</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Belum ada video yang tersedia.</p>
            @endif
            <hr>
            <!-- Display the Field and Players -->
            <h5 class="mt-4">Starting 11</h5>
            <div id="field" 
            style="position: relative; width: 100%; height: 500px; 
                   background: url('{{ asset('img/field.jpg') }}') no-repeat center center; 
                   background-size: cover; border: 2px solid black;">
                @foreach ($datapertandingan->starting11 as $player)
                <div class="player-container" 
                style="position: absolute; left: {{ $player->x }}px; top: {{ $player->y }}px; text-align: center;"
                data-player-id="{{ $player->id }}">

                <!-- Circle for Player -->
                <div class="player-dot"
                    style="width: 40px; height: 40px; background: red; color: white; text-align: center; border-radius: 50%; cursor: grab; display: flex; align-items: center; justify-content: center;"
                    draggable="true">
                </div>

                <!-- Player Name (Below the Circle) -->
                <div class="player-name" 
                    style="margin-top: 5px; font-size: 14px; font-weight: bold;">
                    {{ $player->player_name }}
                </div>
            </div>
                @endforeach
            </div>

            <a href="{{ route('datapertandingan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
