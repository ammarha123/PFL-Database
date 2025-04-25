@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Atur Starting 11 dan Posisi</h2>

    @if(session('success'))
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

    <!-- FORM: Edit Starting 11 Players -->
    <form action="{{ route('datapertandingan.updateStarting11', $datapertandingan->id) }}" method="POST" id="starting11Form">
        @csrf
        @method('PUT')

        <h4>Pilih Pemain</h4>
        @for ($i = 0; $i < 11; $i++)
            @php
                $selectedPlayer = $datapertandingan->starting11[$i] ?? null;
                $selectedPlayerName = $selectedPlayer->player_name ?? '';
                $selectedPosition = $selectedPlayer->position ?? '';
            @endphp
            <div class="row mb-2">
                <!-- Hidden Field to Store Record ID -->
                <input type="hidden" name="players[{{ $i }}][id]" value="{{ $selectedPlayer->id ?? '' }}">

                <!-- Player Selection -->
                <div class="col">
                    <select class="form-control player-select" name="players[{{ $i }}][player_name]" required>
                        <option value="" disabled {{ $selectedPlayerName ? '' : 'selected' }}>Pilih Pemain</option>
                        @foreach ($players as $player)
                            <option value="{{ $player->user->name }}" 
                                {{ $selectedPlayerName == $player->user->name ? 'selected' : '' }}>
                                {{ $player->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Position Selection -->
                <div class="col">
                    <select class="form-control" name="players[{{ $i }}][position]" required>
                        <option value="" disabled {{ $selectedPosition ? '' : 'selected' }}>Pilih Posisi</option>
                        <option value="GK" {{ $selectedPosition == 'GK' ? 'selected' : '' }}>GK (Goalkeeper)</option>
                        <option value="DF" {{ $selectedPosition == 'DF' ? 'selected' : '' }}>DF (Defender)</option>
                        <option value="MF" {{ $selectedPosition == 'MF' ? 'selected' : '' }}>MF (Midfielder)</option>
                        <option value="FW" {{ $selectedPosition == 'FW' ? 'selected' : '' }}>FW (Forward)</option>
                    </select>
                </div>
            </div>
        @endfor

        <button type="submit" class="btn btn-success">Simpan Pemain</button>
    </form>
    <hr>

    <!-- FIELD: Drag & Drop -->
    <h3 class="text-center mt-4">Atur Posisi di Lapangan</h3>
    <div id="field" 
         style="position: relative; width: 100%; height: 500px; 
                background: url('{{ asset('img/field.jpg') }}') no-repeat center center; 
                background-size: cover; border: 2px solid black;">
        
        @foreach ($startingPlayers as $player)
            <div class="player-container" 
                 style="position: absolute; left: {{ $player->x ?? 50 }}px; top: {{ $player->y ?? 50 }}px; text-align: center;"
                 data-player-id="{{ $player->id }}">

                <div class="player-dot"
                     style="width: 40px; height: 40px; background: red; color: white; text-align: center; 
                            border-radius: 50%; cursor: grab; display: flex; align-items: center; justify-content: center;"
                     draggable="true">
                </div>

                <div class="player-name" 
                     style="margin-top: 5px; font-size: 14px; font-weight: bold;">
                    {{ $player->player_name }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- FORM: Save Positions -->
    <form id="positionForm" action="{{ route('datapertandingan.storePositions', ['matchId' => $datapertandingan->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="positions" id="positionsData">
        <button type="submit" class="btn btn-primary mt-3">Simpan Posisi</button>
    </form>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const field = document.getElementById('field');
    const playerContainers = document.querySelectorAll('.player-container');
    const positionsData = document.getElementById('positionsData');
    let positions = [];

    playerContainers.forEach(container => {
        let dot = container.querySelector('.player-dot');

        dot.addEventListener('mousedown', function (e) {
            e.preventDefault();
            let shiftX = e.clientX - container.getBoundingClientRect().left;
            let shiftY = e.clientY - container.getBoundingClientRect().top;

            function moveAt(clientX, clientY) {
                let fieldRect = field.getBoundingClientRect();
                let x = clientX - fieldRect.left - shiftX;
                let y = clientY - fieldRect.top - shiftY;
                container.style.left = `${x}px`;
                container.style.top = `${y}px`;
            }

            function onMouseMove(event) {
                moveAt(event.clientX, event.clientY);
            }

            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', () => {
                document.removeEventListener('mousemove', onMouseMove);
                let playerId = container.dataset.playerId;
                positions.push({ player_id: playerId, x: parseInt(container.style.left), y: parseInt(container.style.top) });
                positionsData.value = JSON.stringify(positions);
            }, { once: true });
        });
    });

    // 🔥 Prevent Duplicate Player Selection
    document.querySelectorAll(".player-select").forEach(select => {
        select.addEventListener("change", () => {
            let selectedPlayers = new Set([...document.querySelectorAll(".player-select")].map(s => s.value));
            document.querySelectorAll(".player-select option").forEach(option => {
                if (option.value && selectedPlayers.has(option.value) && option.selected === false) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        });
    });
});
</script>

@endsection
