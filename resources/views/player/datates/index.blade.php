@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <a href="/" class="btn btn-secondary mb-3">
        &larr; Kembali
    </a>
    <div class="row">
        @include('partial.player-data')

        <div class="row mt-5 text-center">
            <div class="title mb-5">
                <h5>Data Tes</h5>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $category }}</h5>

                                @if (!empty($categorizedTests[$category]) && $categorizedTests[$category]->isNotEmpty())
                                    <ul class="list-group">
                                        @foreach ($categorizedTests[$category] as $test)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ \Carbon\Carbon::parse($test->tanggal)->format('d M Y') }}</span>
                                                <a href="{{ route('player.datates.show', $test->id) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    Lihat Detail
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Belum ada tes</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
