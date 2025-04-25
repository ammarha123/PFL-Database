@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">List Data Pertandingan</li>
        </ol>
    </nav>  

    <h2 class="mb-4 text-center">Data Pertandingan</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (auth()->user()->role === 'Admin')
    <div class="new-pemain mb-3">
        <a class="btn btn-success" href="{{ route('datapertandingan.create') }}">Tambah Data Pertandingan</a>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-filter"></i> Filter Data
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('datapertandingan.index') }}">
                <div class="row">
                    <!-- Month Filter -->
                    <div class="col-md-2">
                        <label for="month">Bulan:</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">Semua</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
        
                    <!-- Year Filter -->
                    <div class="col-md-2">
                        <label for="year">Tahun:</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">Semua</option>
                            @for ($y = now()->year; $y >= now()->year - 10; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
        
                    <!-- Date Range Filter -->
                    <div class="col-md-2">
                        <label for="start_date">Dari Tanggal:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date">Sampai Tanggal:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
        
                    <!-- Team Filter -->
                    <div class="col-md-2">
                        <label for="home_team">Pilih Tim:</label>
                        <select name="home_team" id="home_team" class="form-control">
                            <option value="">Semua Tim</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->home_team }}" {{ request('home_team') == $team->home_team ? 'selected' : '' }}>
                                    {{ $team->home_team }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <!-- Submit Button -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-sucess-modif w-100" id="filterButton">
                            <i class="fas fa-search"></i> Filter
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
     @php
     function sortLink($column, $currentSortBy, $currentSortOrder) {
         $sortOrder = ($currentSortBy === $column && $currentSortOrder === 'asc') ? 'desc' : 'asc';
         return request()->fullUrlWithQuery(['sortBy' => $column, 'sortOrder' => $sortOrder]);
     }
    @endphp

 <table class="table table-bordered table-hover">
     <thead class="thead-light">
         <tr>
             <th>No.</th>
             <th>
                 <a href="{{ sortLink('home_team', $sortBy, $sortOrder) }}" class="text-decoration-none text-dark">
                     Nama Tim
                 </a>
             </th>
             <th>Tim Lawan</th>
             <th>Skor</th>
             <th>
                 <a href="{{ sortLink('location', $sortBy, $sortOrder) }}" class="text-decoration-none text-dark">
                     Stadion
                 </a>
             </th>
             <th>Aksi</th>
         </tr>
     </thead>
     <tbody>
         @forelse ($datapertandingan as $index => $pertandingan)
         <tr>
             <td>{{ $datapertandingan->firstItem() + $index }}</td>
             <td>{{ $pertandingan->home_team }}</td>
             <td>{{ $pertandingan->away_team }}</td>
             <td>{{ $pertandingan->home_score }} - {{ $pertandingan->away_score }}</td>
             <td>{{ $pertandingan->location }}</td>
             <td>
                <a href="{{ route('datapertandingan.show', $pertandingan->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                </a>
                @if (auth()->user()->role === 'Admin')
                <a href="{{ route('datapertandingan.edit', $pertandingan->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('datapertandingan.destroy', $pertandingan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
             <td colspan="6" class="text-center">Tidak ada data pertandingan.</td>
         </tr>
         @endforelse
     </tbody>
 </table>

    <!-- ✅ Pagination -->
    <div class="d-flex justify-content-center">
        {{ $datapertandingan->appends(request()->query())->links() }}
    </div>

</div>
<script>
    document.getElementById('filterButton').addEventListener('click', function() {
        document.getElementById('loadingSpinner').classList.remove('d-none');
    });
</script>
@endsection
