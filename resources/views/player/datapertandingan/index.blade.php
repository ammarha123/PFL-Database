@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        @include('partial.player-data')

        <div class="row mt-5 text-center">
            <div class="title mb-5">
                <h5>Data Pertandingan</h5>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Section -->
            <div class="d-flex align-items-center mb-3">
                <form action="{{ route('player.datapertandingan.index') }}" method="GET" class="d-flex flex-wrap">
                    <label for="year" class="me-2">Tahun</label>
                    <select name="year" class="form-select me-2 w-auto" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>

                    <label for="month" class="me-2">Bulan</label>
                    <select name="month" class="form-select me-2 w-auto" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach($months as $key => $month)
                            <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>

            <!-- Match List -->
            <div class="row">
                @forelse ($datapertandingan as $match)
                    <div class="col-md-4 col-lg-3 mb-3">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('img/trophy-icon.png') }}" class="me-3" alt="Trophy Icon" width="50">
                            <div>
                                <p class="mb-0">
                                    <span class="fw-bold">{{ \Carbon\Carbon::parse($match->tanggal)->format('d.m.y') }}</span>
                                    | vs {{ $match->away_team }}
                                </p>
                                <button class="btn btn-link text-decoration-none" onclick="showMatchDetail({{ $match->id }})">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">Belum ada data pertandingan.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $datapertandingan->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Match Details Modal -->
<div class="modal fade" id="matchDetailModal" tabindex="-1" aria-labelledby="matchDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="matchDetailLabel">Detail Pertandingan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="matchDetailContent">
                <!-- AJAX content will be loaded here -->
            </div>
        </div>
    </div>
</div>
<script>
    function showMatchDetail(matchId) {
        fetch("{{ route('player.datapertandingan.show', '') }}/" + matchId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Match details not found');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById("matchDetailContent").innerHTML = data;
                var myModal = new bootstrap.Modal(document.getElementById('matchDetailModal'));
                myModal.show();
            })
            .catch(error => {
                console.error("Error fetching match details:", error);
                alert("Data pertandingan tidak ditemukan!");
            });
    }
    document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.player-container').forEach(player => {
        let field = document.getElementById('field');
        let maxX = field.clientWidth - 40; // Max width within field
        let maxY = field.clientHeight - 40; // Max height within field

        let left = parseFloat(player.style.left);
        let top = parseFloat(player.style.top);

        // Prevent going out of bounds
        player.style.left = Math.max(0, Math.min(left, maxX)) + 'px';
        player.style.top = Math.max(0, Math.min(top, maxY)) + 'px';
    });
});

</script>
@endsection
