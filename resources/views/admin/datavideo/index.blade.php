@extends('layout.admin')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">List Data Video</li>
        </ol>
    </nav>
    
    <h2 class="mb-4 text-center">Data Video</h2>

    <!-- ✅ Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (auth()->user()->role === 'Admin')
    <div class="new-pemain">
        <a class="btn btn-success my-3" href="{{ route('datavideo.create') }}">Tambah Video</a>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-filter"></i> Filter Data
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('datavideo.index') }}" class="">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label for="video_category" class="form-label">Kategori Video</label>
                        <select class="form-select" id="video_category" name="video_category">
                            <option value="">Pilih Kategori</option>
                            <option value="Full Match" {{ request('video_category') == 'Full Match' ? 'selected' : '' }}>Full Match</option>
                            <option value="Latihan" {{ request('video_category') == 'Latihan' ? 'selected' : '' }}>Latihan</option>
                            <option value="Lainnya" {{ request('video_category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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

    <!-- 📊 Data Table -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No.</th>
                <th>
                    <a href="{{ route('datavideo.index', array_merge(request()->query(), ['sort_by' => 'video_category', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                        Kategori Video
                    </a>
                </th>
                <th>Link Video</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datavideos as $index => $video)
            <tr>
                <td>{{ $datavideos->firstItem() + $index }}</td>
                <td>{{ $video->video_category }}</td>
                <td><a href="{{ $video->link_video }}" target="_blank">Lihat Video</a></td>
                <td>
                        <a href="{{ route('datavideo.show', $video->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if (auth()->user()->role === 'Admin')
                        <a href="{{ route('datavideo.edit', $video->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('datavideo.destroy', $video->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                <td colspan="6" class="text-center">Tidak ada data video.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 📄 Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $datavideos->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
