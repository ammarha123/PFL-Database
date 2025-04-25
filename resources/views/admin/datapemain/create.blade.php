@extends('layout.admin')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary">Dashboard</a></li>
                <li class="breadcrumb-item text-secondary"><a href="{{ route('datapemain.index') }}" class="text-decoration-none text-secondary">Data Pemain</a></li>
                <li class="breadcrumb-item text-success" aria-current="page">Tambah Data Pemain</li>
            </ol>
        </nav>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card shadow-sm my-3">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Tambah Data Pemain</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('datapemain.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
        
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
        
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
        
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
        
                    <div id="player-fields">
                        <!-- Date of Birth -->
                        <div class="mb-3">
                            <label for="bod" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="bod" name="bod">
                        </div>
        
                        <!-- Position -->
                        <div class="mb-3">
                            <label for="position" class="form-label">Posisi</label>
                            <select class="form-select" id="position" name="position">
                                <option selected disabled>Pilih Posisi</option>
                                <option value="Forward">Forward</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Defender">Defender</option>
                                <option value="Goalkeeper">Goalkeeper</option>
                            </select>
                        </div>
        
                        <!-- Team Selection -->
                        <div class="mb-3">
                            <label for="teams" class="form-label">Pilih Tim</label>
                            <select name="teams[]" id="teams" class="form-select" multiple>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Tahan CTRL / CMD untuk memilih lebih dari satu tim</small>
                        </div>
        
                        <!-- Profile Photo -->
                        <div class="mb-3">
                            <label for="photo_profile" class="form-label">Foto Profil</label>
                            <input type="file" name="photo_profile" class="form-control">
                            <small class="text-muted">Format yang didukung: JPG, PNG (Maksimal 2MB)</small>
                        </div>
                    </div>
        
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success my-3">Simpan</button>
                    <a href="{{ route('datapemain.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>

      
    </div>
@endsection
