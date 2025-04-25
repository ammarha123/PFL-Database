@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Data Pertandingan</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
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
            <h5 class="card-title mb-0">Edit Data Pertandingan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('datapertandingan.update', $datapertandingan->id) }}" method="POST">
                @csrf
                @method('PUT')
        
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Pertandingan</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $datapertandingan->tanggal }}" required>
                </div>
        
                <div class="row">
                    <div class="col-md-6">
                        <label for="home_team" class="form-label">Tim Tuan Rumah</label>
                        <input type="text" class="form-control" id="home_team" name="home_team" value="{{ $datapertandingan->home_team }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="away_team" class="form-label">Tim Tamu</label>
                        <input type="text" class="form-control" id="away_team" name="away_team" value="{{ $datapertandingan->away_team }}" required>
                    </div>
                </div>
        
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="home_score" class="form-label">Skor Tuan Rumah</label>
                        <input type="number" class="form-control" id="home_score" name="home_score" value="{{ $datapertandingan->home_score }}" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="away_score" class="form-label">Skor Tim Tamu</label>
                        <input type="number" class="form-control" id="away_score" name="away_score" value="{{ $datapertandingan->away_score }}" min="0" required>
                    </div>
                </div>
        
                <div class="mb-3 mt-3">
                    <label for="location" class="form-label">Lokasi Pertandingan</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ $datapertandingan->location }}" required>
                </div>
        
                 <!-- Match Summary -->
                 <div class="mb-3">
                    <label class="form-label">Match Summary</label>
                    @if ($datapertandingan->match_summary)
                        <p><a href="{{ asset('storage/' . $datapertandingan->match_summary) }}" target="_blank">Lihat File</a></p>
                    @endif
                    <input type="file" class="form-control" name="match_summary">
                </div>
        
                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan Pertandingan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4">{{ $datapertandingan->notes }}</textarea>
                </div>
        
                <!-- Goal Scorers -->
                <div class="mb-3">
                    <label class="form-label">Pencetak Gol</label>
                    <div id="goalScorers">
                        @foreach ($datapertandingan->goals as $index => $goal)
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <input type="text" class="form-control" name="goal_scorers[{{ $index }}][player]" value="{{ $goal->player }}" placeholder="Nama Pemain">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" name="goal_scorers[{{ $index }}][minute]" value="{{ $goal->minute }}" placeholder="Menit" min="1">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addGoalScorer()">Tambah Pencetak Gol</button>
                </div>
        
                <!-- Yellow Cards -->
                <div class="mb-3">
                    <label class="form-label">Kartu Kuning</label>
                    <div id="yellowCards">
                        @foreach ($datapertandingan->yellowCards as $index => $card)
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <input type="text" class="form-control" name="yellow_cards[{{ $index }}][player]" value="{{ $card->player }}" placeholder="Nama Pemain">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" name="yellow_cards[{{ $index }}][minute]" value="{{ $card->minute }}" placeholder="Menit" min="1">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addYellowCard()">Tambah Kartu Kuning</button>
                </div>
        
                <!-- Red Cards -->
                <div class="mb-3">
                    <label class="form-label">Kartu Merah</label>
                    <div id="redCards">
                        @foreach ($datapertandingan->redCards as $index => $card)
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <input type="text" class="form-control" name="red_cards[{{ $index }}][player]" value="{{ $card->player }}" placeholder="Nama Pemain">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" name="red_cards[{{ $index }}][minute]" value="{{ $card->minute }}" placeholder="Menit" min="1">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addRedCard()">Tambah Kartu Merah</button>
                </div>
        
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="{{ route('datapertandingan.editStarting11', $datapertandingan->id) }}" class="btn btn-primary">Lanjut ke Starting 11</a>
            </form>
        </div>
    </div>
</div>

<script>
    function addGoalScorer() {
        let container = document.getElementById('goalScorers');
        let index = container.children.length;
        container.insertAdjacentHTML('beforeend', `
            <div class="row g-2 mb-2">
                <div class="col">
                    <input type="text" class="form-control" name="goal_scorers[${index}][player]" placeholder="Nama Pemain">
                </div>
                <div class="col">
                    <input type="number" class="form-control" name="goal_scorers[${index}][minute]" placeholder="Menit" min="1">
                </div>
            </div>
        `);
    }

    function addYellowCard() {
        let container = document.getElementById('yellowCards');
        let index = container.children.length;
        container.insertAdjacentHTML('beforeend', `
            <div class="row g-2 mb-2">
                <div class="col">
                    <input type="text" class="form-control" name="yellow_cards[${index}][player]" placeholder="Nama Pemain">
                </div>
                <div class="col">
                    <input type="number" class="form-control" name="yellow_cards[${index}][minute]" placeholder="Menit" min="1">
                </div>
            </div>
        `);
    }

    function addRedCard() {
        let container = document.getElementById('redCards');
        let index = container.children.length;
        container.insertAdjacentHTML('beforeend', `
            <div class="row g-2 mb-2">
                <div class="col">
                    <input type="text" class="form-control" name="red_cards[${index}][player]" placeholder="Nama Pemain">
                </div>
                <div class="col">
                    <input type="number" class="form-control" name="red_cards[${index}][minute]" placeholder="Menit" min="1">
                </div>
            </div>
        `);
    }
</script>
@endsection
