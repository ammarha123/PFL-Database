@extends('layout.admin')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('datapemain.index') }}" class="text-decoration-none text-secondary">Data Pemain</a></li>
            <li class="breadcrumb-item active text-success" aria-current="page">Detail</li>
        </ol>
    </nav>

    <!-- Card Layout -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">Detail Data Pemain</h5>
        </div>
        <div class="card-body">
            <!-- Player Information -->
            <div class="row">
                <!-- Left Column: Player Details -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <h5 class="text-success">Nama</h5>
                        <p class="card-text">{{ $datapemain->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-success">Email</h5>
                        <p class="card-text">{{ $datapemain->email }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-success">Tanggal Lahir</h5>
                        <p class="card-text">{{ $datapemain->player->bod }}</p>
                    </div>

                    <div class="">
                        <h5 class="text-success">Posisi</h5>
                        <p class="card-text">{{ $datapemain->player->position }}</p>
                    </div>
                </div>

                <!-- Right Column: Profile Photo and Teams -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <h5 class="text-success">Foto Profil</h5>
                        @if ($datapemain->player->photo_profile)
                            <img src="{{ asset('storage/' . $datapemain->player->photo_profile) }}" 
                                 alt="Foto Profil" 
                                 class="img-fluid rounded shadow-sm" 
                                 style="max-width: 200px;">
                        @else
                            <p class="text-muted">Tidak ada foto profil</p>
                        @endif
                    </div>

                    <div class="">
                        <h5 class="text-success">Tim</h5>
                        @if ($datapemain->player->teams->isNotEmpty())
                            <ul class="list-group">
                                @foreach ($datapemain->player->teams as $team)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $team->name }}
                                        <span class="badge bg-success">{{ $team->pivot->role }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Belum ada tim</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('datapemain.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection