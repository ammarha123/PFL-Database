@extends('layout.admin')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Daftar Evaluasi Mandiri</h4>

    <form method="GET" class="row mb-4">
        <div class="col-md-4">
            <select name="team" class="form-select">
                <option value="">Semua Tim</option>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select name="evaluasi" class="form-select">
                <option value="">Semua Evaluasi</option>
                <option value="ada" {{ request('evaluasi') == 'ada' ? 'selected' : '' }}>Ada Evaluasi</option>
                <option value="tidak" {{ request('evaluasi') == 'tidak' ? 'selected' : '' }}>Belum Ada Evaluasi</option>
            </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-success" type="submit">Filter</button>
            <a href="{{ route('evaluasi_mandiri_admin.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Nama Pemain</th>
                        <th>Tim</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($players as $i => $player)
                        <tr>
                            <td>{{ $players->firstItem() + $i }}</td>
                            <td>{{ $player->user->name }}</td>
                            <td>{{ $player->teams->first()->name ?? '-' }}</td>
                            <td>
                                @if ($player->user->evaluasiMandiri->isNotEmpty())
                                    <a href="{{ route('evaluasi_mandiri_admin.show', $player->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                @else
                                    <span class="text-muted">Belum ada evaluasi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if ($players->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $players->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
