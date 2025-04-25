@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Edit Data Pemain</h2>
    
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
            <h5 class="card-title mb-0">Tambah Data Pemain</h5>
        </div>
        <div class="card-body">
        </div>
    </div>
    <form action="{{ route('datapemain.update', $datapemain->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $datapemain->name }}" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $datapemain->email }}" required>
        </div>

        <!-- Date of Birth -->
        <div class="mb-3">
            <label for="bod" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="bod" name="bod" value="{{ $datapemain->player->bod }}" required>
        </div>

        <!-- Position -->
        <div class="mb-3">
            <label for="position" class="form-label">Posisi</label>
            <select class="form-select" id="position" name="position" required>
                <option value="Forward" {{ $datapemain->player->position == 'Forward' ? 'selected' : '' }}>Forward</option>
                <option value="Midfielder" {{ $datapemain->player->position == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                <option value="Defender" {{ $datapemain->player->position == 'Defender' ? 'selected' : '' }}>Defender</option>
                <option value="Goalkeeper" {{ $datapemain->player->position == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
            </select>
        </div>

        <!-- Team Selection -->
        <div class="mb-3">
            <label for="teams" class="form-label">Pilih Tim</label>
            <select name="teams[]" id="teams" class="form-select" multiple>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" 
                        {{ in_array($team->id, $datapemain->player->teams->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Tahan CTRL / CMD untuk memilih lebih dari satu tim</small>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('datapemain.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
