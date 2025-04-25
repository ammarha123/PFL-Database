<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EvaluasiMandiri;
use Illuminate\Support\Facades\Auth;

class EvaluasiMandiriController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $evaluasiMandiri = EvaluasiMandiri::where('user_id', $user->id)->orderBy('tanggal', 'desc')->get();

        return view('player.evaluasimandiri.index', compact('evaluasiMandiri'));
    }

    public function create()
    {
        return view('player.evaluasimandiri.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'positif_attacking' => 'nullable|string',
            'negatif_attacking' => 'nullable|string',
            'positif_defending' => 'nullable|string',
            'negatif_defending' => 'nullable|string',
            'target_pengembangan_1' => 'nullable|string',
            'target_pengembangan_2' => 'nullable|string',
            'target_pengembangan_3' => 'nullable|string',
        ]);

        // 🔹 Ensure user_id is stored
        $validatedData['user_id'] = Auth::id();

        // 🔹 Create Evaluasi Mandiri record
        EvaluasiMandiri::create($validatedData);

        return redirect()->route('evaluasimandiri.index')->with('success', 'Evaluasi Mandiri berhasil disimpan!');
    }
}
