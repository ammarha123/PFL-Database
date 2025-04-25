@extends('layout.admin')

@section('content')
<div class="container mt-4">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">Tambah Data Video</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('datavideo.store') }}" method="POST">
                @csrf
        
                <!-- Video Category -->
                <div class="mb-3">
                    <label for="video_category" class="form-label">Kategori Video</label>
                    <select class="form-select" id="video_category" name="video_category" required>
                        <option selected disabled>Pilih Kategori</option>
                        <option value="Full Match">Full Match</option>
                        <option value="Latihan">Latihan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
        
                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
        
                <!-- Match Selection -->
                <div class="mb-3" id="match_id_container" style="display: none;">
                    <label for="match_id" class="form-label">Pilih Pertandingan</label>
                    <select class="form-select" id="match_id" name="match_id">
                        <option selected disabled>Pilih Pertandingan</option>
                        @foreach ($matches as $match)
                            <option value="{{ $match->id }}">{{ $match->home_team }} vs {{ $match->away_team }} - {{ $match->tanggal }}</option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Latihan Selection -->
                <div class="mb-3" id="latihan_id_container" style="display: none;">
                    <label for="latihan_id" class="form-label">Pilih Latihan</label>
                    <select class="form-select" id="latihan_id" name="latihan_id">
                        <option selected disabled>Pilih Latihan</option>
                        @foreach ($latihan as $lat)
                            <option value="{{ $lat->id }}">{{ $lat->tanggal }}</option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Video Link -->
                <div class="mb-3">
                    <label for="link_video" class="form-label">Link Video</label>
                    <input type="url" class="form-control" id="link_video" name="link_video" required>
                </div>
        
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('datavideo.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videoCategory = document.getElementById('video_category');
        const matchContainer = document.getElementById('match_id_container');
        const latihanContainer = document.getElementById('latihan_id_container');

        videoCategory.addEventListener('change', function () {
            matchContainer.style.display = 'none';
            latihanContainer.style.display = 'none';

            if (videoCategory.value === 'Full Match') {
                matchContainer.style.display = 'block';
            } else if (videoCategory.value === 'Latihan') {
                latihanContainer.style.display = 'block';
            }
        });
    });
</script>
@endsection
