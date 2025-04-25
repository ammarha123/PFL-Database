@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Isi Evaluasi Mandiri</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('evaluasimandiri.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Positif Attacking</label>
            <textarea class="form-control" name="positif_attacking" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Negatif Attacking</label>
            <textarea class="form-control" name="negatif_attacking" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Defending Positif</label>
            <textarea class="form-control" name="positif_defending" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Defending Negatif</label>
            <textarea class="form-control" name="negatif_defending" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Target Pengembangan</label>
            <input type="text" class="form-control" name="target_pengembangan_1" placeholder="Target 1">
            <input type="text" class="form-control mt-2" name="target_pengembangan_2" placeholder="Target 2">
            <input type="text" class="form-control mt-2" name="target_pengembangan_3" placeholder="Target 3">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
