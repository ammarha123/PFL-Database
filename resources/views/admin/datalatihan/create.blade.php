@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">Isi Data Latihan</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('datalatihan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Tanggal -->
                <div class="mb-3 form-floating">
                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    <label for="tanggal">Tanggal Latihan</label>
                </div>

                <!-- Latihan File -->
                <div class="mb-3">
                    <label for="latihan_file" class="form-label">
                        <i class="fas fa-file-upload me-2"></i>Unggah File Latihan
                    </label>
                    <input type="file" class="form-control" id="latihan_file" name="latihan_file" accept=".pdf,.doc,.docx,.xlsx,.csv">
                </div>

                <!-- Team Selection -->
                <div class="mb-3">
                    <label for="team" class="form-label">
                        <i class="fas fa-users me-2"></i>Pilih Tim
                    </label>
                    <select class="form-select" id="team" name="team_id" required onchange="updatePlayersList()">
                        <option selected disabled>Pilih Tim</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Players Attendance -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-list me-2"></i>List Pemain
                    </label>
                    <div id="players-list" class="list-group">
                        <p class="text-muted">Silakan pilih tim terlebih dahulu</p>
                    </div>
                </div>

                <!-- Submit & Cancel Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('datalatihan.index') }}" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success btn-success-modif">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- JavaScript to Fetch Players Based on Selected Team -->
<script>
    function updatePlayersList() {
        let teamId = document.getElementById("team").value;
        let playersListDiv = document.getElementById("players-list");

        if (!teamId) {
            playersListDiv.innerHTML = '<p class="text-muted">Silakan pilih tim terlebih dahulu</p>';
            return;
        }

        fetch(`/get-players-by-team/${teamId}`)
            .then(response => response.json())
            .then(players => {
                if (players.length === 0) {
                    playersListDiv.innerHTML = '<p class="text-muted">Tidak ada pemain dalam tim ini</p>';
                } else {
                    let playersHtml = players.map(player => `
                          <div class="list-group-item d-flex justify-content-between align-items-center">
                            <input type="hidden" name="players[${player.id}][id]" value="${player.id}">
                            <span>${player.name}</span>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="players[${player.id}][status]" value="Hadir" checked>
                                    <label class="form-check-label">Hadir</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="players[${player.id}][status]" value="Tidak Hadir" onchange="toggleReasonField(${player.id}, this)">
                                    <label class="form-check-label">Tidak Hadir</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="players[${player.id}][reason]" id="reason_${player.id}" placeholder="Masukkan alasan..." style="display: none;">
                            </div>
                        </div>
                    `).join('');

                    playersListDiv.innerHTML = playersHtml;
                }
            })
            .catch(error => {
                console.error("Error fetching players:", error);
                playersListDiv.innerHTML = '<p class="text-danger">Gagal mengambil data pemain.</p>';
            });
    }

    function toggleReasonField(playerId, radio) {
        let reasonField = document.getElementById(`reason_${playerId}`);
        reasonField.style.display = radio.value === 'Tidak Hadir' ? 'block' : 'none';
    }
</script>

@endsection
