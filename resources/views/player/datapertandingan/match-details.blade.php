<div class="container">
    <div class="border p-3">
        <p><strong>TANGGAL :</strong> {{ \Carbon\Carbon::parse($datapertandingan->tanggal)->format('d.m.Y') }}</p>
        <p><strong>TEMPAT :</strong> {{ $datapertandingan->location }}</p>
        <p><strong>TIM :</strong> {{ $datapertandingan->home_team }}</p>
        <p><strong>LAWAN :</strong> {{ $datapertandingan->away_team }}</p>
        <p><strong>STATUS :</strong> {{ $datapertandingan->home_team == 'Persebaya' ? 'HOME' : 'AWAY' }}</p>
    </div>

    <div class="bg-secondary text-white text-center p-2 mt-3">
        <h3>{{ $datapertandingan->home_score }} - {{ $datapertandingan->away_score }}</h3>
    </div>

    <div class="border p-3 text-center">
        <!-- Goals -->
        @foreach ($datapertandingan->goals as $goal)
            <div class="d-flex justify-content-center align-items-center mb-1">
                <span class="fw-bold text-start" style="width: 150px;">{{ $goal->minute }}' {{ $goal->player }}</span>
                <span class="fw-bold" style="width: 150px">GOAL</span>
                <span class="fw-bold text-end" style="width: 150px;"></span>
            </div>
        @endforeach
    
        <!-- Yellow Cards -->
        @foreach ($datapertandingan->yellowCards as $yellow)
            <div class="d-flex justify-content-center align-items-center mb-1">
                <span class="fw-bold text-start" style="width: 150px;">{{ $yellow->minute }}' {{ $yellow->player }}</span>
                <span class="fw-bold" style="color: black; background-color: yellow; border-radius: 3px; width: 150px;">Yellow Card</span>
                <span class="fw-bold text-end" style="width: 150px;"></span>
            </div>
        @endforeach
    
        <!-- Red Cards -->
        @foreach ($datapertandingan->redCards as $red)
            <div class="d-flex justify-content-center align-items-center mb-1">
                <span class="fw-bold text-start" style="width: 150px;">{{ $red->minute }}' {{ $red->player }}</span>
                <span class="fw-bold text-white" style="background-color: red; border-radius: 3px; width: 150px;">Red Card</span>
                <span class="fw-bold text-end" style="width: 150px;"></span>
            </div>
        @endforeach

        @foreach ($datapertandingan->videos as $video)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title">{{ $video->video_category }}</h6>
                    <a href="{{ $video->link_video }}" target="_blank" class="btn btn-primary">
                        Watch Video
                    </a>                    
                </div>
            </div>
        </div>
    @endforeach
    </div>     
    <div id="field-container" class="mt-3" style="display: flex; justify-content: center;">
        <div id="field" 
     style="position: relative; width: 100%; height: 300px; 
            background: url('{{ asset('img/field.jpg') }}') no-repeat center center; 
            background-size: cover; border: 2px solid black;">
             
            @foreach ($datapertandingan->starting11 as $player)
            @php
                $original_width = 1100; 
                $original_height = 730; 
                $new_width = 600;
                $new_height = 400; 
                $scaled_x = ($player->x / $original_width) * $new_width;
                $scaled_y = ($player->y / $original_height) * $new_height;
            @endphp
    
            <div class="player-container" 
                 style="position: absolute; 
                        left: {{ $scaled_x }}px; 
                        top: {{ $scaled_y }}px; 
                        text-align: center;
                        width: 50px;">
    
                <!-- Circle for Player -->
                <div class="player-dot"
                    style="width: 40px; 
                           height: 40px; 
                           background: red; 
                           border-radius: 50%; 
                           display: flex; 
                           align-items: center; 
                           justify-content: center;">
                </div>
    
                <!-- Player Name (Below the Circle) -->
                <div class="player-name" 
                     style="font-size: 12px; 
                            font-weight: bold; 
                            color: white; 
                            margin-top: 5px; 
                            text-align: center;
                            white-space: nowrap;">
                    {{ $player->player_name }}
                </div>
            </div>
            @endforeach
        </div>
    </div>    
</div>
