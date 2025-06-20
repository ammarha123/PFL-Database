@extends('layout.admin')

@section('content')
    <div class="container" style="font-family: 'Segoe UI', sans-serif;">
        <div class="card shadow border-0 my-3" style="background-color: #e8f5e9;">
            <div class="card-body p-4">
                <div class="">
                    <a href="{{ route('rapor_perkembangan.preview', $player->id) }}" class="btn btn-outline-dark mb-3" target="_blank">
                        Download PDF
                    </a>        
                </div>
                <div class="row">
                    <!-- 🧑 Player Info -->
                    <div class="col-md-3 text-center">
                        @if ($player->photo_profile)
                            <img src="{{ asset('storage/' . $player->photo_profile) }}" class="img-fluid rounded mb-2"
                                style="max-height: 200px;" alt="Player Photo">
                        @endif
                        <h5 class="mb-1 text-uppercase">{{ $player->user->name }}</h5>
                        <small class="text-muted">Tanggal Lahir:
                            {{ \Carbon\Carbon::parse($player->bod)->format('d-M-Y') }}</small>

                        <!-- Edit Profile Photo Button -->
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#editPhotoModal">
                                Edit Foto Profil
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                 <div class="col-md-5">
                    <h6 class="fw-bold text-success d-flex justify-content-between align-items-center">
                        EVALUASI SEPAKBOLA
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEvaluasiModal">
                            Edit
                        </button>
                    </h6>
                
                    @php
                        function evalList($label, $positif, $negatif) {
                            $hasData = $positif || $negatif;
                            echo "<div class='mb-3'><strong>{$label}</strong><div class='ms-3'>";
                            if ($hasData) {
                                if ($positif) {
                                    foreach (explode(',', $positif) as $item) {
                                        $clean = trim($item);
                                        if ($clean) echo "<div class=''>+ $clean</div>";
                                    }
                                }
                                if ($negatif) {
                                    foreach (explode(',', $negatif) as $item) {
                                        $clean = trim($item);
                                        if ($clean) echo "<div class=''>- $clean</div>";
                                    }
                                }
                            } else {
                                echo "<div class='text-muted fst-italic'>Data {$label} belum tersedia.</div>";
                            }
                            echo "</div></div>";
                        }
                    @endphp
                
                    @php
                        $evaluasi = $rapor->evaluasi ?? null;
                    @endphp
                
                    {!! evalList('Attack', $evaluasi?->positif_attacking, $evaluasi?->negatif_attacking) !!}
                    {!! evalList('Defend', $evaluasi?->positif_defending, $evaluasi?->negatif_defending) !!}
                    {!! evalList('Transisi', $evaluasi?->positif_transisi, $evaluasi?->negatif_transisi) !!}
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
                        <div class="row">
                            <div class="col d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold text-success">TARGET PERBAIKAN</h6>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTargetModal">
                                    Tambahkan Target
                                </button>
                            </div>
                        </div>
                    
                        @if ($rapor?->targets?->count())
                            <div class="row mt-3">
                                @foreach ($rapor->targets as $i => $target)
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 mb-3">
                                            <h6 class="text-success">{{ sprintf('%02d', $i + 1) }} {{ $target->target }}</h6>
                                            <p><strong>Kapan tercapai?</strong><br>{{ $target->kapan_tercapai }}</p>
                                            <p><strong>Bagaimana mencapainya?</strong><br>{{ $target->cara_mencapai }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning mt-3">Belum ada target perkembangan.</div>
                        @endif
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEvaluasiModal" tabindex="-1" aria-labelledby="editEvaluasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('rapor_perkembangan.update-evaluasi', $player->id) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editEvaluasiModalLabel">Edit Evaluasi Sepakbola</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body row g-3">
                    @php
                        $eval = $rapor->evaluasi ?? null;
                    @endphp
    
                    <div class="col-md-6">
                        <label class="form-label">Attack (+)</label>
                        <textarea class="form-control" name="positif_attacking" rows="2" placeholder="Pisahkan dengan koma (,)">{{ $eval?->positif_attacking }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Attack (-)</label>
                        <textarea class="form-control" name="negatif_attacking" rows="2" placeholder="Pisahkan dengan koma (,)">{{ $eval?->negatif_attacking }}</textarea>
                    </div>
    
                    <div class="col-md-6">
                        <label class="form-label">Defend (+)</label>
                        <textarea class="form-control" name="positif_defending" rows="2" placeholder="Pisahkan dengan koma (,)">{{ $eval?->positif_defending }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Defend (-)</label>
                        <textarea class="form-control" name="negatif_defending" rows="2" placeholder="Pisahkan dengan koma (,)">{{ $eval?->negatif_defending }}</textarea>
                    </div>
    
                    <div class="col-md-6">
                        <label class="form-label">Transisi (+)</label>
                        <textarea class="form-control" name="positif_transisi" rows="2" placeholder="Pisahkan dengan koma (,)">{{ $eval?->positif_transisi }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Transisi (-)</label>
                        <textarea class="form-control" name="negatif_transisi" rows="2" placeholder="Pisahkan dengan koma (,)">{{ $eval?->negatif_transisi }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
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

    <div class="modal fade" id="editTargetModal" tabindex="-1" aria-labelledby="editTargetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('rapor_perkembangan.update-targetperkembangan', $player->id) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editTargetModalLabel">Tambah / Edit Target Perkembangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="target-wrapper">
                        @foreach ($rapor->targets ?? [] as $i => $t)
                        <div class="border rounded p-3 mb-3">
                            <label>Target</label>
                            <input type="text" name="targets[{{ $i }}][target]" value="{{ $t->target }}" class="form-control mb-2">
                            <label>Kapan Tercapai</label>
                            <input type="text" name="targets[{{ $i }}][kapan_tercapai]" value="{{ $t->kapan_tercapai }}" class="form-control mb-2">
                            <label>Bagaimana Mencapainya</label>
                            <textarea name="targets[{{ $i }}][cara_mencapai]" class="form-control">{{ $t->cara_mencapai }}</textarea>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="addTarget()">+ Tambah Target Baru</button>
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

    <script>
        function addTarget() {
            const wrapper = document.getElementById('target-wrapper');
            const count = wrapper.querySelectorAll('.border.p-3').length;
        
            if (count >= 3) {
                alert('Maksimal 3 target perbaikan saja.');
                return;
            }
        
            const index = count;
        
            wrapper.insertAdjacentHTML('beforeend', `
                <div class="border rounded p-3 mb-3">
                    <label>Target</label>
                    <input type="text" name="targets[${index}][target]" class="form-control mb-2">
                    <label>Kapan Tercapai</label>
                    <input type="text" name="targets[${index}][kapan_tercapai]" class="form-control mb-2">
                    <label>Bagaimana Mencapainya</label>
                    <textarea name="targets[${index}][cara_mencapai]" class="form-control"></textarea>
                </div>
            `);
        }
        </script>        
@endsection