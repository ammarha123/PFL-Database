@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Detail Data Tes</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nama Pemain</h5>
            <p class="card-text">{{ $datates->player->user->name ?? 'Unknown' }}</p> <!-- ✅ Fetch Player Name -->

            <h5 class="card-title">Kategori</h5>
            <p class="card-text">{{ $datates->category }}</p>

            <h5 class="card-title">Tanggal Tes</h5>
            <p class="card-text">{{ $datates->tanggal }}</p>

            <!-- ✅ Display test data based on category -->
            <h5 class="card-title">Hasil Tes</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        @if ($datates->category === 'Antropometri')
                            <tr><td>Berat (kg)</td><td>{{ $datates->weight ?? '-' }}</td></tr>
                            <tr><td>Tinggi (cm)</td><td>{{ $datates->height ?? '-' }}</td></tr>
                            <tr><td>BMI</td><td>{{ $datates->bmi ?? '-' }}</td></tr>
                            <tr><td>Fat Chest</td><td>{{ $datates->fat_chest ?? '-' }}</td></tr>
                            <tr><td>Fat Thighs</td><td>{{ $datates->fat_thighs ?? '-' }}</td></tr>
                            <tr><td>Fat Abs</td><td>{{ $datates->fat_abs ?? '-' }}</td></tr>
                            <tr><td>Fat Percentage</td><td>{{ $datates->fat_percentage ?? '-' }}</td></tr>
                        
                        @elseif ($datates->category === 'FMS')
                            <tr><td>Squat (0-3)</td><td>{{ $datates->fms_squat ?? '-' }}</td></tr>
                            <tr><td>Hurdle (0-3)</td><td>{{ $datates->fms_hurdle ?? '-' }}</td></tr>
                            <tr><td>Lunge (0-3)</td><td>{{ $datates->fms_lunge ?? '-' }}</td></tr>
                            <tr><td>Shoulder Mobility (0-3)</td><td>{{ $datates->fms_shoulder ?? '-' }}</td></tr>
                            <tr><td>Leg Raise (0-3)</td><td>{{ $datates->fms_leg_raise ?? '-' }}</td></tr>
                            <tr><td>Push Up (0-3)</td><td>{{ $datates->fms_push_up ?? '-' }}</td></tr>
                            <tr><td>Rotary Stability (0-3)</td><td>{{ $datates->fms_rotary ?? '-' }}</td></tr>
                            <tr><td>Total Score</td><td>{{ $datates->fms_total ?? '-' }}</td></tr>
                        
                        @elseif ($datates->category === 'VO2Max')
                            <tr><td>VO2Max Type</td><td>{{ $datates->vo2max_type ?? '-' }}</td></tr>
                            <tr><td>Durasi (Menit)</td><td>{{ $datates->vo2max_duration ?? '-' }}</td></tr>
                            <tr><td>Kecepatan (km/h)</td><td>{{ $datates->speed ?? '-' }}</td></tr>
                            <tr><td>Konsumsi Oksigen</td><td>{{ $datates->oxygen ?? '-' }}</td></tr>
                            <tr><td>VO2Max Score</td><td>{{ $datates->vo2max_score ?? '-' }}</td></tr>
                        
                        @elseif ($datates->category === 'MAS')
                            <tr><td>MAS Type</td><td>{{ $datates->mas_type ?? '-' }}</td></tr>
                            <tr><td>Kecepatan Maksimal (km/h)</td><td>{{ $datates->mas_speed ?? '-' }}</td></tr>
                            <tr><td>Durasi (Menit)</td><td>{{ $datates->mas_duration ?? '-' }}</td></tr>
                            <tr><td>Jarak (m)</td><td>{{ $datates->mas_distance ?? '-' }}</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <a href="{{ route('datates.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
