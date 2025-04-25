<?php

namespace App\Http\Controllers;

use App\Models\DataVideo;
use App\Models\DataPertandingan;
use App\Models\DataLatihan;
use App\Models\Team;
use Illuminate\Http\Request;

class DataVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = DataVideo::query();

    // 🔍 Filter by Video Category
    if ($request->has('video_category')) {
        $query->where('video_category', $request->video_category);
    }

    // 🔄 Sorting Logic
    if ($request->has('sort_by')) {
        $sortBy = $request->sort_by;
        $sortOrder = $request->sort_order === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortOrder);
    }

    // 🔹 Paginate Results
    $datavideos = $query->paginate(10);

    return view('admin.datavideo.index', compact('datavideos'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $matches = DataPertandingan::all(); // Get all matches
        $latihan = DataLatihan::all(); // Get all latihan sessions
        return view('admin.datavideo.create', compact('matches', 'latihan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'video_category' => 'required|string',
            'description' => 'nullable|string',
            'link_video' => 'required|url',
            'match_id' => 'nullable|exists:datapertandingan,id',
            'latihan_id' => 'nullable|exists:datalatihan,id',
        ]);

        // Ensure only one of match_id or latihan_id is set
        if ($request->video_category === 'Full Match') {
            $validated['latihan_id'] = null;
        } elseif ($request->video_category === 'Latihan') {
            $validated['match_id'] = null;
        } else {
            $validated['match_id'] = null;
            $validated['latihan_id'] = null;
        }

        DataVideo::create($validated);

        return redirect()->route('datavideo.index')->with('success', 'Data video berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $datavideo = DataVideo::findOrFail($id);
    
        return view('admin.datavideo.show', compact('datavideo'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $datavideo = DataVideo::findOrFail($id);
    $matches = DataPertandingan::all();
    $latihan = DataLatihan::all();
    return view('admin.datavideo.edit', compact('datavideo', 'matches', 'latihan'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $datavideo = DataVideo::findOrFail($id);
    
        // ✅ Validate Input
        $validated = $request->validate([
            'video_category' => 'required|string',
            'link_video' => 'required|url',
            'match_id' => 'nullable|exists:datapertandingan,id',
            'latihan_id' => 'nullable|exists:datalatihan,id',
        ]);
    
        // ✅ Ensure proper match/latihan assignment based on category
        if ($request->video_category === 'Full Match') {
            $validated['latihan_id'] = null;
        } elseif ($request->video_category === 'Latihan') {
            $validated['match_id'] = null;
        } else {
            $validated['match_id'] = null;
            $validated['latihan_id'] = null;
        }
    
        // ✅ Update Data
        $datavideo->update($validated);
    
        return redirect()->route('datavideo.index')->with('success', 'Data video berhasil diperbarui!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $datavideo = DataVideo::findOrFail($id);
        $datavideo->delete();

        return redirect()->route('datavideo.index')->with('success', 'Data video berhasil dihapus!');
    }
}
