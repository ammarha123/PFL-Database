<div class="col-12">
    <div class="card menu">
        <div class="row align-items-center p-3" style="background-color: #f2f2f2;">
            <!-- Player Image -->
            <div class="col-2 text-center">
                <img src="{{ asset('img/player1.png') }}" class="img-fluid rounded-circle w-50" alt="Player Image"
                    style="">
            </div>
            <div class="col-3">
                <p class="mb-1"><strong>{{ auth()->user()->name }}</strong></p> <!-- Player Name -->
                <p class="mb-1">{{ $player->bod }}</p> <!-- Date of Birth -->
                <p class="mb-1">{{ $teamName ?? 'No Team Assigned' }}</p> <!-- Team Name -->
                <p class="mb-1">{{ $player->position }}</p> <!-- Player Position -->
            </div>            
            <div class="col-3">
                <!-- Dummy Image for "Ready to Play" -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <img src="{{ asset('img/ready.png') }}" alt="Ready to Play" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row">
                    <div class="col-6">
                        <p class="mb-1">Training Activeness</p>
                        <div class="d-flex align-items-center">
                            <div style="width: 100%; height: 10px; background-color: lightgray; position: relative;">
                                <div style="width: {{ $trainingActiveness }}%; height: 10px; background-color: green;"></div>
                            </div>
                            <span class="ms-2">{{ round($trainingActiveness, 1) }}%</span>
                        </div>
                    </div>                                
                    <div class="col-6">
                        <p class="mb-1">VO2 Max</p>
                        <span class="text-success">57.6</span>
                    </div>
                    <div class="col-6 mt-2">
                        <p class="mb-1">Match Activeness</p>
                        <div class="d-flex align-items-center">
                            <div style="width: 100%; height: 10px; background-color: lightgray; position: relative;">
                                <div style="width: {{ $matchActiveness }}%; height: 10px; background-color: green;"></div>
                            </div>
                            <span class="ms-2">{{ round($matchActiveness, 1) }}%</span>
                        </div>
                    </div>                                
                    <div class="col-6 mt-2">
                        <p class="mb-1">Body Fat</p>
                        <span class="text-danger">13.7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>