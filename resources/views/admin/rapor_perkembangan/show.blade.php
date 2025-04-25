@extends('layout.admin')

@section('content')
    <div class="container mt-4" style="font-family: 'Segoe UI', sans-serif;">
        <div class="card shadow border-0 my-3" style="background-color: #e8f5e9;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <!-- 🧑 Player Info -->
                    <div class="col-md-3 text-center">
                        @if ($player->photo_profile)
                            <img src="{{ asset('storage/' . $player->photo_profile) }}" class="img-fluid rounded mb-2"
                                style="max-height: 200px;" alt="Player Photo">
                        @endif
                        <h5 class="mb-1 text-uppercase">{{ $player->user->name }}</h5>
                        <small class="text-muted">Tahun Lahir:
                            {{ \Carbon\Carbon::parse($player->bod)->format('Y') }}</small>

                        <!-- Edit Profile Photo Button -->
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#editPhotoModal">
                                Edit Foto Profil
                            </button>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <h6 class="fw-bold text-success">KOMPOSISI TUBUH</h6>
                    
                        @if ($datates)
                            <div class="row mb-2">
                                <div class="col-4">Tinggi</div>
                                <div class="col-8">{{ $datates->height ?? '-' }} cm</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">Berat</div>
                                <div class="col-8">{{ $datates->weight ?? '-' }} kg</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">BMI</div>
                                <div class="col-8">{{ $datates->bmi ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">% Lemak</div>
                                <div class="col-8">{{ $datates->fat_percentage ?? '-' }}%</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">VO2Max</div>
                                <div class="col-8">{{ $datates->vo2max ?? '-' }} ml/kg/min</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">MAS</div>
                                <div class="col-8">{{ $datates->mas ?? '-' }} km/jam</div>
                            </div>
                    
                            @php
                            $bmi = $datates->bmi ?? null;
                            $fat = $datates->fat_percentage ?? null;
                        
                            $status = 'Tidak Diketahui';
                            $description = 'Data tidak lengkap.';
                            $alertClass = 'secondary';
                        
                            if ($bmi && $fat) {
                                if ($bmi < 18.5) {
                                    $status = 'KURUS';
                                    $description = 'Perlu peningkatan massa otot dan asupan nutrisi.';
                                    $alertClass = 'info';
                                } elseif ($bmi >= 18.5 && $bmi <= 24.9 && $fat <= 13) {
                                    $status = 'IDEAL ATLET';
                                    $description = 'Komposisi tubuh atletis. Terus pertahankan!';
                                    $alertClass = 'success';
                                } elseif ($bmi >= 18.5 && $bmi <= 24.9 && $fat <= 24) {
                                    $status = 'NORMAL';
                                    $description = 'Perlu terus memelihara berat badan dan % lemak.';
                                    $alertClass = 'success';
                                } elseif ($bmi >= 25 && $bmi < 30) {
                                    $status = 'OVERWEIGHT';
                                    $description = 'Perlu perbaikan pola makan dan aktivitas fisik.';
                                    $alertClass = 'warning';
                                } elseif ($bmi >= 30 || $fat > 25) {
                                    $status = 'OBESITAS';
                                    $description = 'Perlu intervensi dan monitoring ketat.';
                                    $alertClass = 'danger';
                                }
                            }
                        @endphp
                        
                        <div class="alert alert-{{ $alertClass }} p-2 mt-3">
                            <strong>STATUS: {{ $status }}</strong><br>
                            <small>{{ $description }}</small>
                        </div>
                        
                        @else
                            <div class="text-muted fst-italic">
                                Belum ada data tes terbaru untuk pemain ini.
                            </div>
                        @endif
                    </div>                    

                 <!-- ⚽️ Football Evaluation -->
<div class="col-md-4">
    <h6 class="fw-bold text-success">EVALUASI SEPAKBOLA</h6>

    @if ($evaluasi)
        <div class="mb-2"><strong>ATTACK & TRA (-)</strong><br>
            @if (!empty($evaluasi->positif_attacking))
                + {{ $evaluasi->positif_attacking }}<br>
            @endif
            @if (!empty($evaluasi->negatif_attacking))
                - {{ $evaluasi->negatif_attacking }}
            @endif
        </div>

        <div><strong>DEFEND & TRA (+)</strong><br>
            @if (!empty($evaluasi->positif_defending))
                + {{ $evaluasi->positif_defending }}<br>
            @endif
            @if (!empty($evaluasi->negatif_defending))
                - {{ $evaluasi->negatif_defending }}
            @endif
        </div>
    @else
        <p class="text-muted fst-italic">Belum ada data evaluasi sepakbola.</p>
    @endif
</div>


                <hr class="my-4">

                <!-- ⚽️ Position + General Description -->
                <!-- ⚽️ Position + General Description -->
                <div class="row mb-4">
                    <div class="col-md-3 text-center">
                        <h6 class="fw-bold mb-3">Posisi</h6>

                        @php
                            $positionMap = [
                                'GK' => ['top' => '88%', 'left' => '43%'],
                                'LB' => ['top' => '70%', 'left' => '8%'],
                                'CB' => ['top' => '70%', 'left' => '43%'],
                                'RB' => ['top' => '70%', 'left' => '78%'],
                                'CDM' => ['top' => '55%', 'left' => '43%'],
                                'CM' => ['top' => '38%', 'left' => '43%'],
                                'CAM' => ['top' => '25%', 'left' => '43%'],
                                'LW' => ['top' => '25%', 'left' => '8%'],
                                'RW' => ['top' => '25%', 'left' => '78%'],
                                'ST' => ['top' => '4%', 'left' => '43%'],
                            ];

                            $positions = $rapor?->positions->pluck('position_code')->toArray() ?? [];
                        @endphp

                        @if (count($positions))
                            <div class="field-wrapper mb-2">
                                @foreach ($positions as $code)
                                    @if (isset($positionMap[$code]))
                                        <div class="position-dot"
                                            style="top: {{ $positionMap[$code]['top'] }}; left: {{ $positionMap[$code]['left'] }};">
                                            {{ $code }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted fst-italic">Belum ada posisi</p>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <h6 class="fw-bold">DESKRIPSI UMUM</h6>
                        @if ($rapor && $rapor->deskripsi_umum)
                            <blockquote class="blockquote mb-0">
                                <p class="mb-0">{{ $rapor->deskripsi_umum }}</p>
                            </blockquote>
                        @else
                            <p class="text-muted fst-italic">Belum ada deskripsi umum</p>
                        @endif

                        {{-- <div class="mt-3 d-flex gap-4">
                            <div><i class="bi bi-clock"></i> 862 menit</div>
                            <div><i class="bi bi-play-circle"></i> 9 kali</div>
                            <div><i class="bi bi-arrow-repeat"></i> 3 kali</div>
                        </div> --}}

                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#editRaporModal">
                                Edit Posisi & Deskripsi Umum
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if ($evaluasi)
                        <div class="row">
                            <div class="col">
                                <h6 class="fw-bold text-success">3 TARGET PERBAIKAN</h6>
                                <div class="row">
                                    @php
                                        $targets = [
                                            $evaluasi->target_pengembangan_1,
                                            $evaluasi->target_pengembangan_2,
                                            $evaluasi->target_pengembangan_3,
                                        ];
                                    @endphp
    
                                    @foreach ($targets as $i => $target)
                                        @if (!empty($target))
                                            <div class="col-md-4">
                                                <div class="border rounded p-3 mb-3">
                                                    <h6 class="text-success">{{ sprintf('%02d', $i + 1) }} {{ $target }}
                                                    </h6>
                                                    <p><strong>Apa targetnya?</strong><br>{{ $target }}</p>
                                                    <p><strong>Kapan tercapai?</strong><br>Progress Match to match mulai match
                                                        13 - 28.</p>
                                                    <p><strong>Bagaimana mencapainya?</strong><br>- Latihan sesuai kebutuhan
                                                        target ini.</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mt-4">
                            Belum ada data Evaluasi Mandiri untuk target pengembangan.
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('rapor_perkembangan.update-photo', $player->id) }}" method="POST"
                enctype="multipart/form-data" class="modal-content">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPhotoModalLabel">Edit Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="photo_profile" class="form-label">Pilih Foto Baru</label>
                        <input type="file" class="form-control" name="photo_profile" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Posisi dan Deskripsi Umum -->
    <div class="modal fade" id="editRaporModal" tabindex="-1" aria-labelledby="editRaporModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('rapor_perkembangan.update', $player->id) }}" method="POST" class="modal-content">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title" id="editRaporModalLabel">Edit Posisi & Deskripsi Umum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="deskripsi_umum" class="form-label">Deskripsi Umum</label>
                        <textarea class="form-control" name="deskripsi_umum" rows="4" required>{{ $rapor->deskripsi_umum ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Posisi</label>
                        @php
                            $allPositions = ['GK', 'LB', 'CB', 'RB', 'CM', 'LW', 'RW', 'CAM', 'CDM', 'ST'];
                            $selected = $rapor ? $rapor->positions->pluck('position_code')->toArray() : [];
                        @endphp
                        <div class="row">
                            @foreach ($allPositions as $pos)
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="positions[]"
                                            value="{{ $pos }}" id="pos_{{ $pos }}"
                                            {{ in_array($pos, $selected) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="pos_{{ $pos }}">{{ $pos }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection
