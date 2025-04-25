@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Tim</h2>

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
    <form action="{{ route('datatim.update', $team->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Tim</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $team->name }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('datatim.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
