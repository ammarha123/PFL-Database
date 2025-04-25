@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Edit Data Video</h2>

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
            <h5 class="card-title mb-0">Edit Data Video</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('datavideo.update', $datavideo->id) }}" method="POST">
                @csrf
                @method('PUT')
        
                <!-- 🔹 Video Category -->
                <div class="mb-3">
                    <label for="video_category" class="form-label">Kategori Video</label>
                    <select class="form-select" id="video_category" name="video_category" required>
                        <option value="Full Match" {{ $datavideo->video_category === 'Full Match' ? 'selected' : '' }}>Full Match</option>
                        <option value="Latihan" {{ $datavideo->video_category === 'Latihan' ? 'selected' : '' }}>Latihan</option>
                        <option value="Lainnya" {{ $datavideo->video_category === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
        
                <!-- 🔹 Match Selection (Shown only for Full Match) -->
                <div class="mb-3" id="match_section" style="display: none;">
                    <label for="match_id" class="form-label">Pilih Pertandingan</label>
                    <select class="form-select" id="match_id" name="match_id">
                        <option value="">Pilih Pertandingan</option>
                        @foreach ($matches as $match)
                            <option value="{{ $match->id }}" {{ $datavideo->match_id == $match->id ? 'selected' : '' }}>
                                {{ $match->tanggal }} | {{ $match->home_team }} vs {{ $match->away_team }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <!-- 🔹 Training Selection (Shown only for Latihan) -->
                <div class="mb-3" id="latihan_section" style="display: none;">
                    <label for="latihan_id" class="form-label">Pilih Latihan</label>
                    <select class="form-select" id="latihan_id" name="latihan_id">
                        <option value="">Pilih Latihan</option>
                        @foreach ($latihan as $item)
                            <option value="{{ $item->id }}" {{ $datavideo->latihan_id == $item->id ? 'selected' : '' }}>
                                {{ $item->tanggal }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <!-- 🔹 Video Link -->
                <div class="mb-3">
                    <label for="link_video" class="form-label">Link Video</label>
                    <input type="url" class="form-control" id="link_video" name="link_video" value="{{ $datavideo->link_video }}" required>
                </div>
        
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('datavideo.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

   
</div>

<!-- JavaScript to Show/Hide Sections Dynamically -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videoCategory = document.getElementById('video_category');
        const matchSection = document.getElementById('match_section');
        const latihanSection = document.getElementById('latihan_section');

        function toggleSections() {
            if (videoCategory.value === 'Full Match') {
                matchSection.style.display = 'block';
                latihanSection.style.display = 'none';
                document.getElementById('latihan_id').value = ''; // Clear latihan selection
            } else if (videoCategory.value === 'Latihan') {
                matchSection.style.display = 'none';
                latihanSection.style.display = 'block';
                document.getElementById('match_id').value = ''; // Clear match selection
            } else {
                matchSection.style.display = 'none';
                latihanSection.style.display = 'none';
                document.getElementById('match_id').value = '';
                document.getElementById('latihan_id').value = '';
            }
        }

        // Initialize on page load
        toggleSections();
        videoCategory.addEventListener('change', toggleSections);
    });
</script>
@endsection
