@extends('layout.admin')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">List Data Coach</li>
        </ol>
    </nav>
    <h2 class="mb-4 text-center">Data Coach</h2>
    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (auth()->user()->role === 'Admin')
    <div class="mb-3">
        <a class="btn btn-success" href="{{ route('datacoach.create') }}">Tambah Coach</a>
    </div>
    @endif

    <!-- Table -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($coaches as $index => $coach)
            <tr>
                <td>{{ $coaches->firstItem() + $index }}</td>
                <td>{{ $coach->name }}</td>
                <td>{{ $coach->email }}</td>
                <td>
                    <a href="{{ route('datacoach.edit', $coach->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('datacoach.destroy', $coach->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                <td colspan="4" class="text-center">Tidak ada coach yang tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $coaches->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
