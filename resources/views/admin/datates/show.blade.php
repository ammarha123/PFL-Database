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
                        
                        @elseif ($datates->category === 'VO2Max')
                            <tr><td>VO2Max Level</td><td>{{ $datates->vo2max_level ?? '-' }}</td></tr>
                            <tr><td>Jumlah Balikan</td><td>{{ $datates->vo2max_balikan ?? '-' }}</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <a href="{{ route('datates.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
