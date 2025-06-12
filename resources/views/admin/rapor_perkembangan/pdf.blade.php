<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor - {{ $player->user->name }}</title>
    <style>
        .{
            height: fit-content;
        }
        @page {
            size: A4 landscape;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 13px;
            background-color: #ffffff;
            color: #222;
            height: 50%;
        }
        .section-title {
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 6px;
            font-size: 14px;
        }
        .status-alert {
            padding: 8px;
            background-color: #ffcdd2;
            border-left: 5px solid #e53935;
            font-size: 13px;
            margin-top: 5px;
        }
        .target-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 6px;
            background-color: #f8fdf8;
            border-radius: 4px;
        }
        .wrapper {
            border: 2px solid #ccc;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.15);
            background-color: #eef7ee;
        }
        .position-map {
            height: 300px;
            border: 1px solid #ccc;
            position: relative;
            background-color: #fafafa;
            border-radius: 5px;
        }
        .position-dot {
            position: absolute;
            background-color: #4caf50;
            color: white;
            padding: 4px 6px;
            border-radius: 50%;
            font-size: 11px;
            transform: translate(-50%, -50%);
        }
        .plus { color: green; }
        .minus { color: red; }
        .text-muted { color: #777; }
        img.profile {
            width: 90px;
            height: auto;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-spacing: 20px;
        }
        td {
            vertical-align: top;
            width: 33%;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table>
            <tr>
                <!-- Player Info -->
                <td style="text-align: center;">
                    @if ($player->photo_profile)
                        <img src="{{ public_path('storage/' . $player->photo_profile) }}" class="profile" alt="Photo">
                    @endif
                    <h3>{{ strtoupper($player->user->name) }}</h3>
                    <p class="text-muted">Tahun Lahir: {{ \Carbon\Carbon::parse($player->bod)->format('Y') }}</p>
                </td>
    
                <!-- Komposisi Tubuh -->
                <td>
                    <div class="section-title">KOMPOSISI TUBUH</div>
                    <p>Tinggi: {{ $datates?->height ?? '-' }} cm</p>
                    <p>Berat: {{ $datates?->weight ?? '-' }} kg</p>
                    <p>BMI: {{ $datates?->bmi ?? '-' }}</p>
                    <p>% Lemak: {{ $datates?->fat_percentage ?? '-' }}%</p>
                    <p>VO2Max: {{ $datates?->vo2max ?? '-' }} ml/kg/min</p>
                    <p>MAS: {{ $datates?->mas ?? '-' }} km/jam</p>
                    @php
                        $bmi = $datates?->bmi;
                        $fat = $datates?->fat_percentage;
                        $status = 'Tidak Diketahui';
                        $desc = 'Data tidak lengkap.';
                        if ($bmi && $fat) {
                            if ($bmi < 18.5) {
                                $status = 'KURUS'; $desc = 'Perlu peningkatan massa otot dan nutrisi.';
                            } elseif ($bmi >= 18.5 && $bmi <= 24.9 && $fat <= 13) {
                                $status = 'IDEAL ATLET'; $desc = 'Komposisi tubuh atletis. Terus pertahankan!';
                            } elseif ($bmi >= 18.5 && $bmi <= 24.9 && $fat <= 24) {
                                $status = 'NORMAL'; $desc = 'Perlu terus memelihara berat badan dan % lemak.';
                            } elseif ($bmi >= 25 && $bmi < 30) {
                                $status = 'OVERWEIGHT'; $desc = 'Perlu perbaikan pola makan dan aktivitas fisik.';
                            } elseif ($bmi >= 30 || $fat > 25) {
                                $status = 'OBESITAS'; $desc = 'Perlu intervensi dan monitoring ketat.';
                            }
                        }
                    @endphp
                    <div class="status-alert">
                        <strong>STATUS: {{ $status }}</strong><br>
                        <small>{{ $desc }}</small>
                    </div>
                </td>
    
                <!-- Evaluasi -->
                <td>
                    <div class="section-title">EVALUASI SEPAKBOLA</div>
                    @php
                        $eval = $rapor->evaluasi ?? null;
                        function evalList($label, $plus, $min) {
                            echo "<strong>{$label}</strong><br>";
                            if ($plus) foreach (explode(',', $plus) as $v) echo "<div class='plus'>+ " . trim($v) . "</div>";
                            if ($min) foreach (explode(',', $min) as $v) echo "<div class='minus'>- " . trim($v) . "</div>";
                            if (!$plus && !$min) echo "<div class='text-muted'>Belum ada data {$label}</div>";
                        }
                    @endphp
                    {!! evalList('Attack', $eval?->positif_attacking, $eval?->negatif_attacking) !!}
                    {!! evalList('Defend', $eval?->positif_defending, $eval?->negatif_defending) !!}
                    {!! evalList('Transisi', $eval?->positif_transisi, $eval?->negatif_transisi) !!}
                </td>
            </tr>

            <tr>
                <!-- Posisi -->
                <td>
                    <div class="section-title">Posisi</div>
                    <div class="position-map">
                        @php
                            $map = [
                                'GK' => ['top' => '85%', 'left' => '47%'],
                                'LB' => ['top' => '65%', 'left' => '13%'],
                                'CB' => ['top' => '65%', 'left' => '47%'],
                                'RB' => ['top' => '65%', 'left' => '85%'],
                                'CDM' => ['top' => '50%', 'left' => '47%'],
                                'CM' => ['top' => '35%', 'left' => '47%'],
                                'CAM' => ['top' => '20%', 'left' => '47%'],
                                'LW' => ['top' => '20%', 'left' => '13%'],
                                'RW' => ['top' => '20%', 'left' => '85%'],
                                'ST' => ['top' => '5%', 'left' => '47%'],
                            ];
                            $positions = $rapor?->positions->pluck('position_code')->toArray() ?? [];
                        @endphp
                        @foreach ($positions as $p)
                            @if (isset($map[$p]))
                                <div class="position-dot" style="top: {{ $map[$p]['top'] }}; left: {{ $map[$p]['left'] }};">{{ $p }}</div>
                            @endif
                        @endforeach
                    </div>
                </td>
    
                <!-- Deskripsi -->
                <td>
                    <div class="section-title">DESKRIPSI UMUM</div>
                    <p>{{ $rapor->deskripsi_umum ?? '-' }}</p>
                </td>
    
                <!-- Target -->
                <td>
                    <div class="section-title">TARGET PERBAIKAN</div>
                    @if ($rapor->targets->count())
                        @foreach ($rapor->targets as $i => $t)
                            <div class="target-box">
                                <strong>{{ sprintf('%02d', $i + 1) }} {{ $t->target }}</strong><br>
                                <small><strong>Kapan tercapai?</strong> {{ $t->kapan_tercapai }}</small><br>
                                <small><strong>Bagaimana mencapainya?</strong> {{ $t->cara_mencapai }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada target perkembangan.</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
