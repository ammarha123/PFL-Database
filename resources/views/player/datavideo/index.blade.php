@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <a href="/" class="btn btn-secondary mb-3">
        &larr; Kembali
    </a>
    <h2 class="mb-4 text-center">Data Video Anda</h2>

    <div class="row">
        @include('partial.player-data')
        <!-- Full Match Videos -->
        <div class="col-md-4 mb-3 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Full Match</h5>
                    @if (!$matchVideos->isEmpty())
                        @foreach ($matchVideos as $video)
                            <p><a href="{{ $video->link_video }}" target="_blank">{{ basename($video->link_video) }}</a></p>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada video</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Team Analysis Videos -->
        <div class="col-md-4 mb-3 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Latihan</h5>
                    @if (!$trainingVideos->isEmpty())
                        @foreach ($trainingVideos as $video)
                            <p><a href="{{ $video->link_video }}" target="_blank">{{ basename($video->link_video) }}</a></p>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada video</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Individual Analysis Videos -->
        <div class="col-md-4 mb-3 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Video Lainnya</h5>
                    @if (!$otherVideos->isEmpty())
                        @foreach ($otherVideos as $video)
                            <p><a href="{{ $video->link_video }}" target="_blank">{{ basename($video->link_video) }}</a></p>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada video</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
