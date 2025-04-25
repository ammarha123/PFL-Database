@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Daftar Rapor Perkembangan Pemain</h4>

    <!-- 🔍 Filter Form -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Nama Pemain" value="{{ request('name') }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="bod" class="form-control" placeholder="Tahun Lahir (e.g. 2006)" value="{{ request('bod') }}">
        </div>
        <div class="col-md-3">
            <select name="position" class="form-select">
                <option value="">-- Posisi --</option>
                <option value="GK" {{ request('position') == 'GK' ? 'selected' : '' }}>GK</option>
                <option value="DF" {{ request('position') == 'DF' ? 'selected' : '' }}>DF</option>
                <option value="MF" {{ request('position') == 'MF' ? 'selected' : '' }}>MF</option>
                <option value="FW" {{ request('position') == 'FW' ? 'selected' : '' }}>FW</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="team" class="form-select">
                <option value="">-- Tim --</option>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-success w-100">Filter</button>
        </div>
    </form>

    <!-- 📋 Table -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-success">
                <tr>
                    <th>Nama</th>
                    <th>Tahun Lahir</th>
                    <th>Posisi</th>
                    <th>Tim</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($players as $player)
                    <tr>
                        <td>{{ $player->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($player->bod)->format('Y') }}</td>
                        <td>{{ $player->position }}</td>
                        <td>{{ $player->teams->pluck('name')->implode(', ') }}</td>
                        <td>
                            <a href="{{ route('rapor_perkembangan.show', $player->id) }}" class="btn btn-sm btn-primary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada data pemain.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
