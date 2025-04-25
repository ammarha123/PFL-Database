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
            <h5 class="card-title mb-0">Edit Data Latihan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('datalatihan.update', $datalatihan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3 form-floating">
                    <input type="date" class="form-control" id="tanggal" name="tanggal"  value="{{ $datalatihan->tanggal }}" required>
                    <label for="tanggal">Tanggal Latihan</label>
                </div>

                <label class="card-title">File Latihan Saat Ini</label>
                @if($datalatihan->latihan_file_path)
                    <p>
                        <a href="{{ asset('storage/' . $datalatihan->latihan_file_path) }}" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-download me-2"></i>{{ basename($datalatihan->latihan_file_path) }}
                        </a>
                    </p>
                @else
                    <p class="text-muted">Tidak ada file latihan.</p>
                @endif
        
                <!-- Upload New File -->
                <div class="mb-3">
                    <label for="latihan_file" class="form-label">
                        <i class="fas fa-file-upload me-2"></i>Unggah File Latihan Baru (Opsional)
                    </label>
                    <input type="file" class="form-control" id="latihan_file" name="latihan_file" accept=".pdf,.doc,.docx,.xlsx,.csv">
                </div>
        
                <!-- Team Selection -->
                <div class="mb-3">
                    <label for="team_id" class="form-label"> <i class="fas fa-users me-2"></i>Pilih Tim</label>
                    <select class="form-select" id="team_id" name="team_id" required onchange="updatePlayersList()">
                        <option selected disabled>Pilih Tim</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ $datalatihan->teams->contains('id', $team->id) ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <h5 class="card-title text-success">List Pemain</h5>
                <div id="players-list" class="list-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select_all" onchange="toggleAllPlayers()">
                        <label class="form-check-label fw-bold" for="select_all">Pilih Semua</label>
                    </div>
                
                    @foreach ($players as $player)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $player['name'] }}</span>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input hadir-tidakHadir" type="radio" 
                                        name="players[{{ $player['id'] }}][status]" value="Hadir" 
                                        onclick="updateAttendance({{ $player['id'] }}, 'Hadir')" 
                                        {{ $player['status'] == 'Hadir' ? 'checked' : '' }}>
                                    <label class="form-check-label">Hadir</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input hadir-tidakHadir" type="radio" 
                                        name="players[{{ $player['id'] }}][status]" value="Tidak Hadir" 
                                        onclick="updateAttendance({{ $player['id'] }}, 'Tidak Hadir')" 
                                        {{ $player['status'] == 'Tidak Hadir' ? 'checked' : '' }}>
                                    <label class="form-check-label">Tidak Hadir</label>
                                </div>
                                <input type="text" class="form-control ms-3 reason-field" 
                                    name="players[{{ $player['id'] }}][reason]" id="reason_{{ $player['id'] }}" 
                                    placeholder="Masukkan alasan..." 
                                    value="{{ $player['reason'] }}" 
                                    style="display: {{ $player['status'] == 'Tidak Hadir' ? 'block' : 'none' }};">
                            </div>
                        </div>
                    @endforeach
                </div>
        
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('datalatihan.index') }}" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

   
</div>

<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        updatePlayersList(); // Load players when the page is opened
    });

    function updateAttendance(playerId, status) {
        let reasonField = document.getElementById(`reason_${playerId}`);
        reasonField.style.display = status === 'Tidak Hadir' ? 'block' : 'none';
    }

    function toggleAllPlayers() {
        let checkboxes = document.querySelectorAll(".player-checkbox");
        let selectAll = document.getElementById("select_all").checked;
        checkboxes.forEach(checkbox => checkbox.checked = selectAll);
    }

    function updatePlayersList() {
        let teamId = document.getElementById("team_id").value;
        let playersList = document.getElementById("players-list");

        if (!teamId) {
            playersList.innerHTML = '<p class="text-muted">Pilih tim terlebih dahulu.</p>';
            return;
        }

        fetch(`/get-team-players/${teamId}`)
            .then(response => response.json())
            .then(players => {
                playersList.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select_all" onchange="toggleAllPlayers()">
                        <label class="form-check-label fw-bold" for="select_all">Pilih Semua</label>
                    </div>
                `;

                players.forEach(player => {
                    playersList.innerHTML += `
                        <div class="d-flex align-items-center mb-2">
                            <input type="hidden" name="players[${player.id}][id]" value="${player.id}">

                            <span class="me-3">${player.name}</span>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input hadir-tidakHadir" type="radio" 
                                    name="players[${player.id}][status]" value="Hadir"
                                    onclick="updateAttendance(${player.id}, 'Hadir')">
                                <label class="form-check-label">Hadir</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input hadir-tidakHadir" type="radio" 
                                    name="players[${player.id}][status]" value="Tidak Hadir"
                                    onclick="updateAttendance(${player.id}, 'Tidak Hadir')">
                                <label class="form-check-label">Tidak Hadir</label>
                            </div>

                            <input type="text" class="form-control ms-3 reason-field" 
                                name="players[${player.id}][reason]" id="reason_${player.id}" 
                                placeholder="Masukkan alasan..." 
                                style="display: none;">
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error("Error fetching players:", error);
                playersList.innerHTML = '<p class="text-danger">Gagal mengambil data pemain.</p>';
            });
    }
</script>
@endsection
