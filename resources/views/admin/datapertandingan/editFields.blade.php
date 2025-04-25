@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Posisi Pemain</h2>

    <div id="field" 
     style="position: relative; width: 100%; height: 500px; 
            background: url('{{ asset('img/field.jpg') }}') no-repeat center center; 
            background-size: cover; border: 2px solid black;">
        @foreach ($datapertandingan->starting11 as $player)
        <div class="player-container" 
             style="position: absolute; left: {{ $player->x }}px; top: {{ $player->y }}px; text-align: center;"
             data-player-id="{{ $player->id }}">

            <!-- Circle for Player -->
            <div class="player-dot"
                 style="width: 40px; height: 40px; background: red; color: white; text-align: center; border-radius: 50%; cursor: grab; display: flex; align-items: center; justify-content: center;">
            </div>

            <!-- Player Name (Below the Circle) -->
            <div class="player-name" 
                 style="margin-top: 5px; font-size: 14px; font-weight: bold;">
                {{ $player->player_name }}
            </div>
        </div>
        @endforeach
    </div>

    <form action="{{ route('datapertandingan.updatePositions', $datapertandingan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="positions" id="positionsData">
        <button type="submit" class="btn btn-primary mt-3">Simpan Posisi</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let field = document.getElementById('field');
        let players = document.querySelectorAll('.player-container');
        let positionsData = document.getElementById('positionsData');
        let positions = [];

        players.forEach(player => {
            let isDragging = false;
            let offsetX, offsetY;

            player.addEventListener('mousedown', (e) => {
                isDragging = true;
                offsetX = e.clientX - player.getBoundingClientRect().left;
                offsetY = e.clientY - player.getBoundingClientRect().top;
                player.style.cursor = "grabbing";
            });

            document.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                
                let fieldRect = field.getBoundingClientRect();
                let x = Math.round(e.clientX - fieldRect.left - offsetX);
                let y = Math.round(e.clientY - fieldRect.top - offsetY);

                // Prevent going outside the field
                x = Math.max(0, Math.min(fieldRect.width - 40, x));
                y = Math.max(0, Math.min(fieldRect.height - 40, y));

                player.style.left = `${x}px`;
                player.style.top = `${y}px`;
            });

            document.addEventListener('mouseup', () => {
                if (isDragging) {
                    isDragging = false;
                    player.style.cursor = "grab";

                    let playerId = player.getAttribute('data-player-id');
                    let x = parseInt(player.style.left);
                    let y = parseInt(player.style.top);

                    positions = positions.filter(pos => pos.player_id !== playerId);
                    positions.push({ player_id: playerId, x: x, y: y });

                    positionsData.value = JSON.stringify(positions);
                }
            });
        });
    });
</script>

@endsection
