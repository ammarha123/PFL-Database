@extends('layout.admin')

@section('content')
<div class="container mt-4">
    
    <h2 class="mb-4 text-center">Atur Posisi Starting 11</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div id="field" 
     style="position: relative; width: 100%; height: 500px; 
            background: url('{{ asset('img/field.jpg') }}') no-repeat center center; 
            background-size: cover; border: 2px solid black;">
        @foreach ($players as $player)
            <div class="player-container" 
                style="position: absolute; left: {{ $player->x }}px; top: {{ $player->y }}px; text-align: center;"
                data-player-id="{{ $player->id }}">

                <!-- Circle for Player -->
                <div class="player-dot"
                    style="width: 40px; height: 40px; background: red; color: white; text-align: center; border-radius: 50%; cursor: grab; display: flex; align-items: center; justify-content: center;"
                    draggable="true">
                </div>

                <!-- Player Name (Below the Circle) -->
                <div class="player-name" 
                    style="margin-top: 5px; font-size: 14px; font-weight: bold;">
                    {{ $player->player_name }}
                </div>
            </div>
        @endforeach
    </div>
    
    <form action="{{ route('datapertandingan.storePositions', ['matchId' => $datapertandingan->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="positions" id="positionsData">
        <button type="submit" class="btn btn-primary mt-3">Simpan Posisi</button>
    </form>
</div>

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

                    // Ensure boundaries inside field
                    x = Math.max(0, Math.min(fieldRect.width - 40, x));
                    y = Math.max(0, Math.min(fieldRect.height - 40, y));

                    container.style.left = `${x}px`;
                    container.style.top = `${y}px`;
                }

                function onMouseMove(event) {
                    moveAt(event.clientX, event.clientY);
                }

                document.addEventListener('mousemove', onMouseMove);

                document.addEventListener('mouseup', function () {
                    document.removeEventListener('mousemove', onMouseMove);

                    // Update positions
                    let playerId = container.dataset.playerId;
                    let x = Math.round(parseInt(container.style.left));
                    let y = Math.round(parseInt(container.style.top));

                    positions = positions.filter(pos => pos.player_id !== playerId);
                    positions.push({ player_id: playerId, x: x, y: y });

                    positionsData.value = JSON.stringify(positions);
                }, { once: true });
            });

            dot.ondragstart = () => false; // Disable default drag event
        });

        // Ensure positions are always sent (avoid empty input)
        document.querySelector("form").addEventListener("submit", function (e) {
            if (positions.length === 0) {
                alert("Silakan pindahkan setidaknya satu pemain sebelum menyimpan.");
                e.preventDefault();
            }
        });
    });
</script>

@endsection
