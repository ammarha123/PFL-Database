@extends('layout.admin')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Tambah Data Tes</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('datates.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>

                    <!-- 🔹 Test Type Selection -->
                    <div class="mb-3">
                        <label for="test_type" class="form-label">Jenis Tes</label>
                        <select class="form-select" id="test_type" name="test_type" required>
                            <option selected disabled>Pilih Jenis Tes</option>
                            <option value="individual">Individu</option>
                            <option value="team">Tim</option>
                        </select>
                    </div>

                    <div class="mb-3" id="individual_section" style="display: none;">
                        <label for="player_id" class="form-label">Nama Pemain</label>
                        <select class="form-select" id="player_id" name="player_id">
                            <option selected disabled>Pilih Pemain</option>
                            @foreach ($players as $player)
                                <option value="{{ $player->id }}" data-bod="{{ $player->bod }}">
                                    {{ $player->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 🔹 Team Selection (For Team Test) -->
                    <div class="mb-3" id="team_section" style="display: none;">
                        <label for="team_id" class="form-label">Nama Tim</label>
                        <select class="form-select" id="team_id" name="team_id">
                            <option selected disabled>Pilih Tim</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 🔹 Category Selection -->
                    <div class="mb-3" id="category_section" style="display: none;">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category" required>
                            <option selected disabled>Pilih Kategori</option>
                            <option value="Antropometri">Antropometri</option>
                            <option value="FMS">FMS</option>
                            <option value="VO2Max">VO2Max</option>
                            <option value="MAS">MAS</option>
                        </select>
                    </div>

                    <!-- 🔹 Dynamic Inputs for Individual Player -->
                    <div id="individual_inputs_section"></div>

                    <!-- 🔹 Player List for Team Test (Dynamically Populated) -->
                    <div id="team_players_section" style="display: none;">
                        <h4>Daftar Pemain</h4>
                        <div id="players_list"></div>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const testType = document.getElementById('test_type');
            const individualSection = document.getElementById('individual_section');
            const teamSection = document.getElementById('team_section');
            const teamPlayersSection = document.getElementById('team_players_section');
            const categorySection = document.getElementById('category_section');
            const individualSelect = document.getElementById('player_id');
            const teamSelect = document.getElementById('team_id');
            const categorySelect = document.getElementById('category');
            const playersList = document.getElementById('players_list');
            const individualInputsSection = document.getElementById('individual_inputs_section');

            // Hide all sections initially
            individualSection.style.display = 'none';
            teamSection.style.display = 'none';
            teamPlayersSection.style.display = 'none';
            categorySection.style.display = 'none';
            individualInputsSection.innerHTML = '';

            testType.addEventListener('change', function() {
                individualInputsSection.innerHTML = '';
                playersList.innerHTML = '';

                if (this.value === 'individual') {
                    individualSection.style.display = 'block';
                    categorySection.style.display = 'block';
                    teamSection.style.display = 'none';
                    teamPlayersSection.style.display = 'none';
                } else if (this.value === 'team') {
                    individualSection.style.display = 'none';
                    categorySection.style.display = 'block';
                    teamSection.style.display = 'block';
                    teamPlayersSection.style.display = 'block';
                }
            });

            categorySelect.addEventListener('change', function() {
                if (testType.value === 'individual') {
                    const selectedOption = individualSelect.options[individualSelect.selectedIndex];
                    const bod = selectedOption.dataset.bod;
                    if (!bod) return;

                    const age = calculateAge(bod);
                    individualInputsSection.innerHTML = '';
                    const row = document.createElement('div');
                    row.className = 'row';
                    row.dataset.age = age;
                    row.innerHTML = generateInputFields(null);
                    individualInputsSection.appendChild(row);
                    setupLiveCalculations();
                } else {
                    teamSelect.dispatchEvent(new Event('change'));
                }
            });


            individualSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const bod = selectedOption.dataset.bod;

                if (!bod) return;

                const age = calculateAge(bod);
                console.log(`Selected player: BOD = ${bod}, Age = ${age}`);

                individualInputsSection.innerHTML = ''; // 🔧 ensures no stale rows
                const row = document.createElement('div');
                row.className = 'row';
                row.dataset.age = age;
                row.innerHTML = generateInputFields(null);
                individualInputsSection.appendChild(row);
                setupLiveCalculations();
            });

            teamSelect.addEventListener('change', function() {
                let teamId = this.value;
                if (!teamId || teamId === "Pilih Tim") {
                    playersList.innerHTML = '<p class="text-danger">Pilih tim terlebih dahulu.</p>';
                    return;
                }

                fetch(`/get-team-players/${teamId}`)
                    .then(response => response.json())
                    .then(players => {
                        playersList.innerHTML = '';
                        players.forEach(player => {
                            const age = calculateAge(player.bod);
                            console.log(
                                `Player: ${player.name}, BOD: ${player.bod}, Age: ${age}`
                                ); // 🔍 Debug line

                            playersList.innerHTML += `
            <div class="mb-2">
                <label>${player.name}</label>
                <div class="row" data-age="${age}">
                    ${generateInputFields(player.id)}
                </div>
            </div>`;
                        });
                        setupLiveCalculations();
                    })
                    .catch(error => {
                        console.error("Error fetching players:", error);
                        playersList.innerHTML =
                            '<p class="text-danger">Gagal mengambil data pemain.</p>';
                    });
            });

            function generateInputFields(playerId = null) {
                let category = categorySelect.value;
                let fields = '';
                let prefix = testType.value === 'team' ? `players[${playerId}]` : '';

                if (category === 'Antropometri') {
                    fields += `
                    <div class="col"><input type="number" step="0.1" class="form-control my-2" name="${prefix ? prefix + '[weight]' : 'weight'}" placeholder="Berat (kg)"></div>
                    <div class="col"><input type="number" step="0.1" class="form-control my-2" name="${prefix ? prefix + '[height]' : 'height'}" placeholder="Tinggi (cm)"></div>
                    <div class="col"><input type="number" step="0.1" class="form-control my-2" name="${prefix ? prefix + '[bmi]' : 'bmi'}" placeholder="BMI" readonly></div>
                    <div class="col"><input type="number" step="0.1" class="form-control my-2" name="${prefix ? prefix + '[fat_chest]' : 'fat_chest'}" placeholder="Fat Chest (mm)"></div>
                    <div class="col"><input type="number" step="0.1" class="form-control my-2" name="${prefix ? prefix + '[fat_thighs]' : 'fat_thighs'}" placeholder="Fat Thighs (mm)"></div>
                    <div class="col"><input type="number" step="0.1" class="form-control my-2" name="${prefix ? prefix + '[fat_abs]' : 'fat_abs'}" placeholder="Fat Abs (mm)"></div>
                    <div class="col"><input type="number" step="0.1" class="form-control my-2" name="${prefix ? prefix + '[fat_percentage]' : 'fat_percentage'}" placeholder="Fat Overall (%)" readonly></div>
                `;
                }

                // Add other categories (FMS, VO2Max, MAS) if needed...

                return fields;
            }
        });

        // Calculate age from BOD
        function calculateAge(bod) {
            const birthDate = new Date(bod);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        // Bind live calculation to inputs
        function setupLiveCalculations() {
            const inputs = document.querySelectorAll(
                'input[name$="[weight]"], input[name$="[height]"], input[name$="[fat_chest]"], input[name$="[fat_thighs]"], input[name$="[fat_abs]"],' +
                'input[name="weight"], input[name="height"], input[name="fat_chest"], input[name="fat_thighs"], input[name="fat_abs"]'
            );

            inputs.forEach(input => input.addEventListener('input', calculateAll));
        }

        // Do the math
        function calculateAll() {
            const isTeam = document.getElementById('test_type').value === 'team';
            const sections = isTeam ? document.querySelectorAll('#players_list > div') : [document];

            sections.forEach(section => {
                let prefix = '';
                if (isTeam) {
                    const idMatch = section.innerHTML.match(/players\[(\d+)\]/);
                    prefix = idMatch ? `players[${idMatch[1]}]` : '';
                }

                const get = (name) => section.querySelector(
                    `input[name="${prefix ? prefix + '[' + name + ']' : name}"]`);

                const weight = parseFloat(get('weight')?.value);
                const height = parseFloat(get('height')?.value);
                const bmiField = get('bmi');

                const chest = parseFloat(get('fat_chest')?.value);
                const thigh = parseFloat(get('fat_thighs')?.value);
                const abs = parseFloat(get('fat_abs')?.value);
                const fatField = get('fat_percentage');

                // BMI Calculation
                if (isFinite(weight) && isFinite(height) && height > 0 && bmiField) {
                    const heightM = height / 100;
                    const bmi = weight / (heightM * heightM);
                    bmiField.value = bmi.toFixed(1);
                } else if (bmiField) {
                    bmiField.value = '';
                }

                // Fat Percentage Calculation
                if (isFinite(chest) && isFinite(thigh) && isFinite(abs) && fatField) {
                    const sum = chest + thigh + abs;
                    let age = 22;
                    const row = section.querySelector('.row');
                    if (row?.dataset.age) {
                        const parsedAge = parseInt(row.dataset.age);
                        if (!isNaN(parsedAge)) age = parsedAge;
                    }

                    console.log("🔍 Fat % Calculation Debug:");
                    console.log(`Chest: ${chest} mm`);
                    console.log(`Thigh: ${thigh} mm`);
                    console.log(`Abs: ${abs} mm`);
                    console.log(`Sum of skinfolds: ${sum} mm`);
                    console.log(`Age: ${age} years`);

                    const density = 1.10938 - (0.0008267 * sum) + (0.0000016 * sum * sum) - (0.0002574 * age);
                    const fat = (495 / density) - 450;

                    if (isFinite(fat)) {
                        fatField.value = fat.toFixed(1);
                    } else {
                        fatField.value = '';
                    }
                } else if (fatField) {
                    fatField.value = '';
                }
            });
        }
    </script>
@endsection
