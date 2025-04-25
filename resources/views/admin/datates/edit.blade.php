@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Edit Data Tes</h2>

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
            <h5 class="card-title mb-0">Edit Data Tes</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('datates.update', $datates->id) }}" method="POST">
                @csrf
                @method('PUT')
        
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Pertandingan</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $datates->tanggal }}" required>
                </div>
        
                <!-- 🔹 Player Selection -->
                <div class="mb-3">
                    <label for="player_id" class="form-label">Nama Pemain</label>
                    <input type="text" class="form-control" value="{{ $datates->player->user->name }}" readonly>
                    <input type="hidden" name="player_id" value="{{ $datates->player_id }}">
                </div>
        
                <!-- 🔹 Category Selection -->
                <div class="mb-3">
                    <label for="category" class="form-label">Kategori</label>
                    <input type="text" class="form-control" value="{{ $datates->category }}" readonly>
                    <input type="hidden" name="category" value="{{ $datates->category }}">
                </div>
        
                <!-- 🔹 Dynamic Input Fields Based on Category -->
                <div id="test_fields">
                    @if ($datates->category === 'Antropometri')
                        <div class="mb-3">
                            <label for="weight" class="form-label">Berat (kg)</label>
                            <input type="number" step="0.1" class="form-control" id="weight" name="weight" value="{{ $datates->weight }}">
                        </div>
                        <div class="mb-3">
                            <label for="height" class="form-label">Tinggi (cm)</label>
                            <input type="number" step="0.1" class="form-control" id="height" name="height" value="{{ $datates->height }}">
                        </div>
                        <div class="mb-3">
                            <label for="bmi" class="form-label">BMI</label>
                            <input type="number" step="0.1" class="form-control" id="bmi" name="bmi" value="{{ $datates->bmi }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="fat_chest" class="form-label">Fat Chest (mm)</label>
                            <input type="number" step="0.1" class="form-control" id="fat_chest" name="fat_chest" value="{{ $datates->fat_chest }}">
                        </div>
                        <div class="mb-3">
                            <label for="fat_thighs" class="form-label">Fat Thighs (mm)</label>
                            <input type="number" step="0.1" class="form-control" id="fat_thighs" name="fat_thighs" value="{{ $datates->fat_thighs }}">
                        </div>
                        <div class="mb-3">
                            <label for="fat_abs" class="form-label">Fat Abs (mm)</label>
                            <input type="number" step="0.1" class="form-control" id="fat_abs" name="fat_abs" value="{{ $datates->fat_abs }}">
                        </div>
                        <div class="mb-3">
                            <label for="fat_percentage" class="form-label">Fat Percentage (%)</label>
                            <input type="number" step="0.1" class="form-control" id="fat_percentage" name="fat_percentage" value="{{ $datates->fat_percentage }}" readonly>
                        </div>
                    @elseif ($datates->category === 'FMS')
                        <div class="mb-3">
                            <label for="fms_squat" class="form-label">Squat (0-3)</label>
                            <input type="number" class="form-control" id="fms_squat" name="fms_squat" value="{{ $datates->fms_squat }}">
                        </div>
                        <div class="mb-3">
                            <label for="fms_hurdle" class="form-label">Hurdle (0-3)</label>
                            <input type="number" class="form-control" id="fms_hurdle" name="fms_hurdle" value="{{ $datates->fms_hurdle }}">
                        </div>
                        <div class="mb-3">
                            <label for="fms_lunge" class="form-label">Lunge (0-3)</label>
                            <input type="number" class="form-control" id="fms_lunge" name="fms_lunge" value="{{ $datates->fms_lunge }}">
                        </div>
                    @elseif ($datates->category === 'VO2Max')
                        <div class="mb-3">
                            <label for="vo2max_score" class="form-label">VO2Max Score</label>
                            <input type="number" step="0.1" class="form-control" id="vo2max_score" name="vo2max_score" value="{{ $datates->vo2max_score }}">
                        </div>
                        <div class="mb-3">
                            <label for="oxygen" class="form-label">Oxygen Level</label>
                            <input type="number" step="0.1" class="form-control" id="oxygen" name="oxygen" value="{{ $datates->oxygen }}">
                        </div>
                    @elseif ($datates->category === 'MAS')
                        <div class="mb-3">
                            <label for="mas_speed" class="form-label">Speed (km/h)</label>
                            <input type="number" step="0.1" class="form-control" id="mas_speed" name="mas_speed" value="{{ $datates->mas_speed }}">
                        </div>
                        <div class="mb-3">
                            <label for="mas_distance" class="form-label">Distance (m)</label>
                            <input type="number" step="0.1" class="form-control" id="mas_distance" name="mas_distance" value="{{ $datates->mas_distance }}">
                        </div>
                    @endif
                </div>
        
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('datates.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

{{-- 🧠 BMI + Fat % Calculation --}}
<script>
    const playerBOD = "{{ $datates->player->bod }}";

    document.addEventListener('DOMContentLoaded', function () {
        if (!playerBOD) return;

        const age = calculateAge(playerBOD);
        console.log(`Editing: BOD = ${playerBOD}, Age = ${age}`);

        const weightInput = document.getElementById('weight');
        const heightInput = document.getElementById('height');
        const bmiInput = document.getElementById('bmi');

        const chestInput = document.getElementById('fat_chest');
        const thighInput = document.getElementById('fat_thighs');
        const absInput = document.getElementById('fat_abs');
        const fatInput = document.getElementById('fat_percentage');

        function setupListeners() {
            if (weightInput && heightInput && bmiInput) {
                [weightInput, heightInput].forEach(el => {
                    el.addEventListener('input', calculateBMI);
                });
            }

            if (chestInput && thighInput && absInput && fatInput) {
                [chestInput, thighInput, absInput].forEach(el => {
                    el.addEventListener('input', calculateFatPercentage);
                });
            }
        }

        function calculateBMI() {
            const weight = parseFloat(weightInput.value);
            const height = parseFloat(heightInput.value);
            if (isFinite(weight) && isFinite(height) && height > 0) {
                const heightM = height / 100;
                const bmi = weight / (heightM * heightM);
                bmiInput.value = bmi.toFixed(1);
            } else {
                bmiInput.value = '';
            }
        }

        function calculateFatPercentage() {
            const chest = parseFloat(chestInput.value);
            const thigh = parseFloat(thighInput.value);
            const abs = parseFloat(absInput.value);

            if (isFinite(chest) && isFinite(thigh) && isFinite(abs)) {
                const sum = chest + thigh + abs;

                const density = 1.10938
                    - (0.0008267 * sum)
                    + (0.0000016 * sum * sum)
                    - (0.0002574 * age);

                const fat = (495 / density) - 450;
                fatInput.value = isFinite(fat) ? fat.toFixed(1) : '';
                console.log(`🧠 Fat % Calculated: ${fat.toFixed(1)}% from skinfold sum = ${sum}, age = ${age}`);
            } else {
                fatInput.value = '';
            }
        }

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

        setupListeners();
    });
</script>
@endsection
