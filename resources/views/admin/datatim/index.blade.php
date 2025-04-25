@extends('layout.admin')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">List Data Tim</li>
        </ol>
    </nav>
    <h2 class="text-center mb-4">Data Tim</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (auth()->user()->role === 'Admin')
    <div class="mb-3">
        <a class="btn btn-success" href="{{ route('datatim.create') }}">Tambah Tim</a>
    </div>
    @endif

    <!-- Table -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Tim</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($teams as $index => $team)
            <tr>
                <td>{{ $teams->firstItem() + $index }}</td>
                <td>{{ $team->name }}</td>
                <td>
                    <a href="{{ route('datatim.edit', $team->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('datatim.destroy', $team->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada tim yang tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $teams->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
