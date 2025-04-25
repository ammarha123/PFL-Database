@extends('layout.admin')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">List Data Tes</li>
        </ol>
    </nav>
    <h2 class="mb-4 text-center">Data Tes</h2>

    <!-- ✅ Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (auth()->user()->role === 'Admin')
    <div class="mb-3">
        <a class="btn btn-success my-3" href="{{ route('datates.create') }}">Tambah Data Tes</a>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-filter"></i> Filter Data
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('datates.index') }}">
                <div class="row g-2">
                    <!-- 🔹 Filter by Date Range -->
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
        
                    <!-- 🔹 Filter by Player Name -->
                    <div class="col-md-3">
                        <label for="player_id" class="form-label">Nama Pemain</label>
                        <select class="form-select" id="player_id" name="player_id">
                            <option value="">Pilih Pemain</option>
                            @foreach ($players as $player)
                                <option value="{{ $player->id }}" {{ request('player_id') == $player->id ? 'selected' : '' }}>
                                    {{ $player->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <!-- 🔹 Filter by Category -->
                    <div class="col-md-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Pilih Kategori</option>
                            <option value="Antropometri" {{ request('category') == 'Antropometri' ? 'selected' : '' }}>Antropometri</option>
                            <option value="FMS" {{ request('category') == 'FMS' ? 'selected' : '' }}>FMS</option>
                            <option value="VO2Max" {{ request('category') == 'VO2Max' ? 'selected' : '' }}>VO2Max</option>
                            <option value="MAS" {{ request('category') == 'MAS' ? 'selected' : '' }}>MAS</option>
                        </select>
                    </div>
        
                    <!-- 🔹 Filter & Reset Buttons -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filter</button>
                        <a href="{{ route('datates.index') }}" class="btn btn-secondary w-100 ms-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 🔍 Filter Section -->
    

    <!-- 📋 Data Table -->
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>No.</th>
                <th>
                    Nama Pemain
                </th>
                <th>
                    <a href="{{ route('datates.index', array_merge(request()->query(), ['sort_by' => 'category', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                        Kategori
                    </a>
                </th>
                <th>
                    <a href="{{ route('datates.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                        Tanggal Tes
                    </a>
                </th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datates as $index => $tes)
            <tr>
                <td>{{ $datates->firstItem() + $index }}</td>
                <td>
                    @if($tes->player && $tes->player->user)
                        {{ $tes->player->user->name }}
                    @else
                        <span class="text-danger">Unknown</span>
                    @endif
                </td>                
                <td>{{ $tes->category }}</td>
                <td>{{ $tes->tanggal }}</td>
                <td>
                    <a href="{{ route('datates.show', $tes->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if (auth()->user()->role === 'Admin')
                    <a href="{{ route('datates.edit', $tes->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('datates.destroy', $tes->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data tes.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 📌 Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $datates->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
