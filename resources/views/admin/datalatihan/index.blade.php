@extends('layout.admin')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">List Data Latihan</li>
        </ol>
    </nav>

    <h2 class="mb-4 text-center">Data Latihan</h2>   

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (auth()->user()->role === 'Admin')
    <div class="new-pemain mb-3">
        <a class="btn btn-success" href="{{ route('datalatihan.create') }}">Tambah Data Latihan</a>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-filter"></i> Filter Data
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('datalatihan.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="team_id" class="form-label">Nama Tim</label>
                        <select class="form-select" id="team_id" name="team_id">
                            <option value="">Pilih Tim</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-sucess-modif w-100" id="filterButton">
                            <i class="fas fa-search"></i> Filter
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-light">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Nama File</th>
                <th>Nama Tim</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datalatihan as $index => $item)
            <tr>
                <td>{{ $datalatihan->firstItem() + $index }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>
                    <a href="{{ asset('storage/' . $item->latihan_file_path) }}" target="_blank">
                        {{ basename($item->latihan_file_path) }}
                    </a>
                </td>
                <td>
                    @php
                        // ✅ Fetch, sort, and display team names
                        $sortedTeams = $item->teams->pluck('name')->sort()->values()->toArray();
                        if (request('sort_order') === 'desc') {
                            $sortedTeams = array_reverse($sortedTeams);
                        }
                    @endphp
                    {{ implode(', ', $sortedTeams) }}
                </td>
                <td>
                    <a href="{{ route('datalatihan.show', $item->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                    <a href="{{ route('datalatihan.edit', $item->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('datalatihan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data latihan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>    

    <div class="d-flex justify-content-center mt-3">
        {{ $datalatihan->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
    document.getElementById('filterButton').addEventListener('click', function() {
        document.getElementById('loadingSpinner').classList.remove('d-none');
    });
</script>
@endsection
