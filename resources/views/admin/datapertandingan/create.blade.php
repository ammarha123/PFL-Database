@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Tambah Data Pertandingan</h2>

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
            <h5 class="card-title mb-0">Tambah Data Pertandingan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('datapertandingan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
        
                <!-- Match Date -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Pertandingan</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                </div>
        
                <!-- Teams and Score -->
                <div class="row">
                    <div class="col-md-6">
                        <label for="home_team" class="form-label">Nama Tim</label>
                        <select class="form-select" id="home_team" name="home_team" required>
                            <option selected disabled>Pilih Tim</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->name }}">{{ $team->name }}</option>  <!-- ✅ Retrieve team name from DB -->
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="away_team" class="form-label">Tim Lawan</label>
                        <input type="text" class="form-control" id="away_team" name="away_team" required>
                    </div>
                </div>
        
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="home_score" class="form-label">Skor Tuan Rumah</label>
                        <input type="number" class="form-control" id="home_score" name="home_score" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="away_score" class="form-label">Skor Tim Tamu</label>
                        <input type="number" class="form-control" id="away_score" name="away_score" min="0" required>
                    </div>
                </div>
        
                <!-- Location -->
                <div class="mb-3 mt-3">
                    <label for="location" class="form-label">Lokasi Pertandingan</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
        
                <div class="mb-3 mt-3">
                    <label for="match_summary" class="form-label">Match Summary</label>
                    <input type="file" class="form-control" id="match_summary" name="match_summary" accept=".pdf,.doc,.docx">
                </div>
        
               <!-- Goal Scorers -->
               <div class="mb-3">
                <label class="form-label">Pencetak Gol</label>
                <div id="goalScorers">
                    <div class="row g-2">
                        <div class="col">
                            <select class="form-control" name="goal_scorers[0][player]">
                                <option value="" disabled selected>Pilih Pemain</option>
                                @foreach ($players as $player)
                                    <option value="{{ $player->user->name }}">{{ $player->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" name="goal_scorers[0][minute]" placeholder="Menit" min="1">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addGoalScorer()">Tambah Pencetak Gol</button>
            </div>
        
            <!-- Yellow Cards -->
            <div class="mb-3">
                <label class="form-label">Kartu Kuning</label>
                <div id="yellowCards">
                    <div class="row g-2">
                        <div class="col">
                            <select class="form-control" name="yellow_cards[0][player]">
                                <option value="" disabled selected>Pilih Pemain</option>
                                @foreach ($players as $player)
                                    <option value="{{ $player->user->name }}">{{ $player->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" name="yellow_cards[0][minute]" placeholder="Menit" min="1">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addYellowCard()">Tambah Kartu Kuning</button>
            </div>
        
            <!-- Red Cards -->
            <div class="mb-3">
                <label class="form-label">Kartu Merah</label>
                <div id="redCards">
                    <div class="row g-2">
                        <div class="col">
                            <select class="form-control" name="red_cards[0][player]">
                                <option value="" disabled selected>Pilih Pemain</option>
                                @foreach ($players as $player)
                                    <option value="{{ $player->user->name }}">{{ $player->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" name="red_cards[0][minute]" placeholder="Menit" min="1">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addRedCard()">Tambah Kartu Merah</button>
            </div>
        
                <!-- Match Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan Pertandingan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Masukkan catatan tambahan (opsional)"></textarea>
                </div>
        
                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">Simpan dan Tambah Starting 11</button>
            </form>
        </div>
    </div>
  
</div>

<script>
    let goalIndex = 1;
    let yellowIndex = 1;
    let redIndex = 1;

    function addGoalScorer() {
        const container = document.getElementById('goalScorers');
        container.insertAdjacentHTML('beforeend', `  
            <div class="row g-2 mt-2">
                <div class="col">
                    <select class="form-control" name="goal_scorers[${goalIndex}][player]">
                        <option value="" disabled selected>Pilih Pemain</option>
                        @foreach ($players as $player)
                            <option value="{{ $player->user->name }}">{{ $player->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="number" class="form-control" name="goal_scorers[${goalIndex}][minute]" placeholder="Menit" min="1">
                </div>
            </div>
        `);
        goalIndex++;
    }

    function addYellowCard() {
        const container = document.getElementById('yellowCards');
        container.insertAdjacentHTML('beforeend', `
            <div class="row g-2 mt-2">
                 <div class="col">
                    <select class="form-control" name="yellow_cards[${yellowIndex}][player]">
                        <option value="" disabled selected>Pilih Pemain</option>
                        @foreach ($players as $player)
                            <option value="{{ $player->user->name }}">{{ $player->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="number" class="form-control" name="yellow_cards[${yellowIndex}][minute]" placeholder="Menit" min="1">
                </div>
            </div>
        `);
        yellowIndex++;
    }

    function addRedCard() {
        const container = document.getElementById('redCards');
        container.insertAdjacentHTML('beforeend', `
            <div class="row g-2 mt-2">
                 <div class="col">
                    <select class="form-control" name="red_cards[${redIndex}][player]">
                        <option value="" disabled selected>Pilih Pemain</option>
                        @foreach ($players as $player)
                            <option value="{{ $player->user->name }}">{{ $player->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="number" class="form-control" name="red_cards[${redIndex}][minute]" placeholder="Menit" min="1">
                </div>
            </div>
        `);
        redIndex++;
    }
</script>
@endsection
