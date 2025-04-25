@extends('layout.admin')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">List Data Pemain</li>
        </ol>
    </nav>
    <h2 class="mb-4 text-center">Data Pemain</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (auth()->user()->role === 'Admin')
        <div class="d-flex align-items-center mb-3">
            <a class="btn btn-success me-2" href="{{ route('datapemain.create') }}">Tambah Pemain Baru</a>
            <form action="{{ route('datapemain.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" id="fileInput" class="d-none" required onchange="this.form.submit()">
                <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click();">
                    Import Excel
                </button>
            </form>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-filter"></i> Filter Data
        </div>
        <div class="card-body">
             <!-- Filters and Search (Side by Side) -->
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <form action="{{ route('datapemain.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Cari pemain...">
            
            <!-- Filter by Position -->
            <select name="position" class="form-select me-2" onchange="this.form.submit()">
                <option value="">Pilih Posisi</option>
                <option value="Goalkeeper" {{ request('position') == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                <option value="Defender" {{ request('position') == 'Defender' ? 'selected' : '' }}>Defender</option>
                <option value="Midfielder" {{ request('position') == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                <option value="Forward" {{ request('position') == 'Forward' ? 'selected' : '' }}>Forward</option>
            </select>

            <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        <!-- Items Per Page Dropdown -->
        <form action="{{ route('datapemain.index') }}" method="GET">
            <label for="perPage" class="me-2">Tampilkan</label>
            <select name="perPage" id="perPage" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>
    </div>
        </div>
    </div>

    <!-- Data Table -->
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th><a class="text-decoration-none text-dark" href="{{ route('datapemain.index', array_merge(request()->query(), ['sortBy' => 'name', 'sortDirection' => request('sortDirection') == 'asc' ? 'desc' : 'asc'])) }}">Nama</a></th>
                <th><a class="text-decoration-none text-dark" href="{{ route('datapemain.index', array_merge(request()->query(), ['sortBy' => 'bod', 'sortDirection' => request('sortDirection') == 'asc' ? 'desc' : 'asc'])) }}">Tanggal Lahir</a></th>
                <th>Email</th>
                <th>Posisi</th>
                <th>Tim</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datapemain as $index => $player)
                <tr>
                    <td>{{ $datapemain->firstItem() + $index }}</td>
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->player->bod ?? '-' }}</td>
                    <td>{{ $player->email }}</td>
                    <td>{{ $player->player->position ?? '-' }}</td>
                    <td>
                        @if ($player->player && $player->player->teams->isNotEmpty())
                            {{ $player->player->teams->pluck('name')->join(', ') }}
                        @else
                            <span class="text-muted">Belum ada tim</span>
                        @endif
                    </td>
                    <td>{{ $player->role }}</td>                    
                    <td>
                        <a href="{{ route('datapemain.show', $player->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if (auth()->user()->role === 'Admin')
                        <a href="{{ route('datapemain.edit', $player->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('datapemain.destroy', $player->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                    <td colspan="8" class="text-center">Tidak ada data pemain.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $datapemain->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
