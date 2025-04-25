<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPertandingan;

class DataPertandinganController extends Controller
{
    public function index(Request $request)
    {
        // Filter by year and month
        $query = DataPertandingan::query();

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        $datapertandingan = $query->orderBy('tanggal', 'desc')->paginate(12);

        // Get distinct years and months for filtering
        $years = DataPertandingan::selectRaw('YEAR(tanggal) as year')->distinct()->pluck('year');
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        return view('player.datapertandingan.index', compact('datapertandingan', 'years', 'months'));
    }

    public function show($id)
    {
        $datapertandingan = DataPertandingan::with(['goals', 'yellowCards', 'redCards', 'starting11'])->find($id);
    
        if (!$datapertandingan) {
            return response()->json(['error' => 'Data pertandingan tidak ditemukan!'], 404);
        }
    
        return view('player.datapertandingan.match-details', compact('datapertandingan'));
    }
    
}
