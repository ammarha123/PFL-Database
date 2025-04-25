@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-center">Detail Tes</h2>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">{{ $test->category }}</h5>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($test->tanggal)->format('d M Y') }}</p>
            
            <table class="table table-bordered">
                <tbody>
                        <tr><td>Berat (kg)</td><td>{{ $test->weight ?? '-' }}</td></tr>
                        <tr><td>Tinggi (cm)</td><td>{{ $test->height ?? '-' }}</td></tr>
                        <tr><td>BMI</td><td>{{ $test->bmi ?? '-' }}</td></tr>
                        <tr><td>Fat Abs</td><td>{{ $test->fat_abs ?? '-' }}</td></tr>
                        <tr><td>Fat Chest</td><td>{{ $test->fat_chest ?? '-' }}</td></tr>
                        <tr><td>Fat Thighs</td><td>{{ $test->fat_thighs ?? '-' }}</td></tr>
                        <tr><td>Persentase Lemak</td><td>{{ $test->fat_percentage ?? '-' }}</td></tr>
                </tbody>
            </table>

            <a href="{{ route('player.datates.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
