@extends('layout.admin')

@section('content')
<div class="container">
    <a href="/" class="btn btn-secondary mb-3">
        &larr; Kembali
    </a>

    @include('partial.player-data')
    <h2 class="mb-4 mt-5 text-center">Rapor Perkembangan</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

   
    @if($raporList->isEmpty())
        <p>Tidak ada data rapor perkembangan.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Deskripsi Umum</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($raporList as $index => $rapor)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ Str::limit($rapor->deskripsi_umum, 100) }}</td>
                        <td>{{ $rapor->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('player.raporperkembangan.show', $rapor->id) }}" class="btn btn-info btn-sm">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
