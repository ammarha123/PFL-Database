
@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Data Coach</h2>

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
            <h5 class="card-title mb-0">Edit Data Coach</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('datacoach.update', $coach->id) }}" method="POST">
                @csrf
                @method('PUT')
        
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $coach->name }}" required>
                </div>
        
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $coach->email }}" required>
                </div>
        
                <!-- Password (Optional) -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password (Opsional - kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
        
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('datacoach.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
    
</div>
@endsection

