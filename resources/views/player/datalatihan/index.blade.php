@extends('layout.admin')

@section('content')
    <div class="container mt-4">
        <div class="row">
            @include('partial.player-data')
            <div class="row mt-5">
                <div class="title">
                    <h5>Data Latihan</h5>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($datalatihan->isEmpty())
                    <p class="text-center text-muted">Belum ada data latihan yang tersedia.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>File Latihan</th>
                                <th>Video Latihan</th> <!-- Add Video Column -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datalatihan as $latihan)
                                <tr>
                                    <td>{{ $latihan->tanggal }}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $latihan->latihan_file_path) }}" download>
                                            {{ basename($latihan->latihan_file_path) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($latihan->videos->isNotEmpty())
                                            <ul>
                                                @foreach ($latihan->videos as $video)
                                                    <li>
                                                        <a href="{{ $video->link_video }}" target="_blank">
                                                            {{ $video->video_category }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">Belum ada video</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>                        
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
