@extends('layout.admin')

@section('content')
    <div class="container" style="font-family: 'Segoe UI', sans-serif;">
        <div class="text-end mb-3">
            <a href="{{ route('rapor_perkembangan.download-pdf', $player->id) }}" class="btn btn-success">
                ⬇️ Unduh PDF
            </a>
        </div>
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
                        <small class="text-muted">Tanggal Lahir:
                            {{ \Carbon\Carbon::parse($player->bod)->format('d-M-Y') }}</small>
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
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold text-success">TARGET PERBAIKAN</h6>
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
@endsection