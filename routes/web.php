<?php

use App\Http\Controllers\DataLatihanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataCoachController;
use App\Http\Controllers\DataPemainController;
use App\Http\Controllers\DataPertandinganController;
use App\Http\Controllers\DataTesController;
use App\Http\Controllers\DataTimController;
use App\Http\Controllers\DataVideoController;
use App\Http\Controllers\EvaluasiMandiriController as ControllersEvaluasiMandiriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Player\DataLatihanController as PlayerDataLatihanController;
use App\Http\Controllers\Player\DataPertandinganController as PlayerDataPertandinganController;
use App\Http\Controllers\Player\DataTesController as PlayerDataTesController;
use App\Http\Controllers\Player\DataVideoController as PlayerDataVideoController;
use App\Http\Controllers\Player\RaporPerkembanganController as PlayerRaporPerkembanganController;
use App\Http\Controllers\Player\EvaluasiMandiriController;
use App\Http\Controllers\RaporPerkembanganController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);

    Route::get('/datalatihan', [DataLatihanController::class, 'index'])->name('datalatihan.index');
    Route::get('/datalatihan/create', [DataLatihanController::class, 'create'])->name('datalatihan.create');
    Route::post('/datalatihan', [DataLatihanController::class, 'store'])->name('datalatihan.store');
    Route::get('/datalatihan/{datalatihan}', [DataLatihanController::class, 'show'])->name('datalatihan.show');
    Route::get('datalatihan/{datalatihan}/edit', [DataLatihanController::class, 'edit'])->name('datalatihan.edit');
    Route::put('/datalatihan/{datalatihan}', [DataLatihanController::class, 'update'])->name('datalatihan.update');
    Route::delete('/datalatihan/{datalatihan}', [DataLatihanController::class, 'destroy'])->name('datalatihan.destroy');
    Route::get('/get-players-by-team/{teamId}', [DataLatihanController::class, 'getPlayersByTeam']);


    Route::get('/datapemain', [DataPemainController::class, 'index'])->name('datapemain.index');
    Route::get('/datapemain/create', [DataPemainController::class, 'create'])->name('datapemain.create');
    Route::post('/datapemain', [DataPemainController::class, 'store'])->name('datapemain.store');
    Route::get('/datapemain/{datapemain}', [DataPemainController::class, 'show'])->name('datapemain.show');
    Route::get('datapemain/{datapemain}/edit', [DataPemainController::class, 'edit'])->name('datapemain.edit');
    Route::put('/datapemain/{datapemain}', [DataPemainController::class, 'update'])->name('datapemain.update');
    Route::delete('/datapemain/{datapemain}', [DataPemainController::class, 'destroy'])->name('datapemain.destroy');
    Route::post('/datapemain/import', [DataPemainController::class, 'importExcel'])->name('datapemain.import');

    
    Route::get('/datatim', [DataTimController::class, 'index'])->name('datatim.index');
    Route::get('/datatim/create', [DataTimController::class, 'create'])->name('datatim.create');
    Route::post('/datatim', [DataTimController::class, 'store'])->name('datatim.store');
    Route::get('/datatim/{datatim}', [DataTimController::class, 'show'])->name('datatim.show');
    Route::get('datatim/{datatim}/edit', [DataTimController::class, 'edit'])->name('datatim.edit');
    Route::put('/datatim/{datatim}', [DataTimController::class, 'update'])->name('datatim.update');
    Route::delete('/datatim/{datatim}', [DataTimController::class, 'destroy'])->name('datatim.destroy');

    Route::get('/datacoach', [DataCoachController::class, 'index'])->name('datacoach.index');
    Route::get('/datacoach/create', [DataCoachController::class, 'create'])->name('datacoach.create');
    Route::post('/datacoach', [DataCoachController::class, 'store'])->name('datacoach.store');
    Route::get('/datacoach/{datacoach}', [DataCoachController::class, 'show'])->name('datacoach.show');
    Route::get('datacoach/{datacoach}/edit', [DataCoachController::class, 'edit'])->name('datacoach.edit');
    Route::put('/datacoach/{datacoach}', [DataCoachController::class, 'update'])->name('datacoach.update');
    Route::delete('/datacoach/{datacoach}', [DataCoachController::class, 'destroy'])->name('datacoach.destroy');

    Route::get('/datavideo', [DataVideoController::class, 'index'])->name('datavideo.index');
    Route::get('/datavideo/create', [DataVideoController::class, 'create'])->name('datavideo.create');
    Route::post('/datavideo', [DataVideoController::class, 'store'])->name('datavideo.store');
    Route::get('/datavideo/{datavideo}', [DataVideoController::class, 'show'])->name('datavideo.show');
    Route::get('datavideo/{datavideo}/edit', [DataVideoController::class, 'edit'])->name('datavideo.edit');
    Route::put('/datavideo/{datavideo}', [DataVideoController::class, 'update'])->name('datavideo.update');
    Route::delete('/datavideo/{datavideo}', [DataVideoController::class, 'destroy'])->name('datavideo.destroy');

    Route::get('/datates', [DataTesController::class, 'index'])->name('datates.index');
    Route::get('/datates/create', [DataTesController::class, 'create'])->name('datates.create');
    Route::post('/datates', [DataTesController::class, 'store'])->name('datates.store');
    Route::get('/datates/{datates}', [DataTesController::class, 'show'])->name('datates.show');
    Route::get('/get-team-players/{team_id}', [DataTesController::class, 'getTeamPlayers']);
    Route::get('datates/{datates}/edit', [DataTesController::class, 'edit'])->name('datates.edit');
    Route::put('/datates/{datates}', [DataTesController::class, 'update'])->name('datates.update');
    Route::delete('/datates/{datates}', [DataTesController::class, 'destroy'])->name('datates.destroy');

    Route::get('/datapertandingan', [DataPertandinganController::class, 'index'])->name('datapertandingan.index');
    Route::get('/datapertandingan/create', [DataPertandinganController::class, 'create'])->name('datapertandingan.create');
    Route::post('/datapertandingan', [DataPertandinganController::class, 'store'])->name('datapertandingan.store');
    // Route::get('/datapertandingan/{match_id}/starting11', [DataPertandinganController::class, 'starting11Page'])->name('datapertandingan.starting11');
    // Route::post('/datapertandingan/{match_id}/starting11', [DataPertandinganController::class, 'storeStarting11'])->name('datapertandingan.starting11.store');
    // Route::get('/datapertandingan/{match_id}/dragdrop', [DataPertandinganController::class, 'dragDropPage'])->name('datapertandingan.dragdrop');
    // Route::post('datapertandingan/{matchId}/dragdrop', [DataPertandinganController::class, 'storePositions'])->name('datapertandingan.storePositions');
    Route::get('/datapertandingan/{datapertandingan}', [DataPertandinganController::class, 'show'])->name('datapertandingan.show');
    Route::get('datapertandingan/{datapertandingan}/edit', [DataPertandinganController::class, 'edit'])->name('datapertandingan.edit');
    Route::get('datapertandingan/{datapertandingan}/editStarting11', [DataPertandinganController::class, 'editStarting11'])
    ->name('datapertandingan.editStarting11');
    Route::put('datapertandingan/{datapertandingan}/updateStarting11', [DataPertandinganController::class, 'updateStarting11'])
    ->name('datapertandingan.updateStarting11');
    Route::get('datapertandingan/{datapertandingan}/editPositions', [DataPertandinganController::class, 'editPositions'])
    ->name('datapertandingan.editPositions');
    Route::put('datapertandingan/{datapertandingan}/updatePositions', [DataPertandinganController::class, 'updatePositions'])
    ->name('datapertandingan.updatePositions');
    Route::put('/datapertandingan/{datapertandingan}', [DataPertandinganController::class, 'update'])->name('datapertandingan.update');
    Route::delete('/datapertandingan/{datapertandingan}', [DataPertandinganController::class, 'destroy'])->name('datapertandingan.destroy')
    ;
    
    Route::get('/rapor-perkembangan', [RaporPerkembanganController::class, 'index'])->name('rapor_perkembangan.index');
    Route::get('/rapor-perkembangan/{player}', [RaporPerkembanganController::class, 'show'])->name('rapor_perkembangan.show');
    Route::post('/rapor-perkembangan/{player}/update-photo', [RaporPerkembanganController::class, 'updatePhoto'])->name('rapor_perkembangan.update-photo');
    Route::post('/rapor-perkembangan/{player}/update', [RaporPerkembanganController::class, 'update'])->name('rapor_perkembangan.update');
    Route::post('/rapor-perkembangan/{player}/update-targetperkembangan', [RaporPerkembanganController::class, 'updateTargetPerkembangan'])->name('rapor_perkembangan.update-targetperkembangan');
    Route::post('/rapor-perkembangan/{player}/update-evaluasi', [RaporPerkembanganController::class, 'updateEvaluasi'])->name('rapor_perkembangan.update-evaluasi');
    Route::get('/rapor-perkembangan/{player}/preview', [RaporPerkembanganController::class, 'preview'])->name('rapor_perkembangan.preview');
    Route::get('/rapor-perkembangan/{player}/download-pdf', [RaporPerkembanganController::class, 'downloadPDF'])->name('rapor_perkembangan.download-pdf');

    Route::get('/admin/evaluasi-mandiri', [ControllersEvaluasiMandiriController::class, 'index'])->name('evaluasi_mandiri_admin.index');
    Route::get('/admin/evaluasi-mandiri/{id}', [ControllersEvaluasiMandiriController::class, 'show'])->name('evaluasi_mandiri_admin.show');

    Route::middleware(['auth', 'role:Player'])->group(function () {
        Route::get('/player/datalatihan', [PlayerDataLatihanController::class, 'index'])->name('player.datalatihan.index');
        Route::get('/evaluasimandiri', [EvaluasiMandiriController::class, 'index'])->name('evaluasimandiri.index');
        Route::get('/evaluasimandiri/create', [EvaluasiMandiriController::class, 'create'])->name('evaluasimandiri.create');
        Route::post('/evaluasimandiri/store', [EvaluasiMandiriController::class, 'store'])->name('evaluasimandiri.store');
        Route::get('/player/datates', [PlayerDataTesController::class, 'index'])->name('player.datates.index');
        Route::get('/player/datates/{id}', [PlayerDataTesController::class, 'show'])->name('player.datates.show');
        Route::get('/player/datavideo', [PlayerDataVideoController::class, 'index'])->name('player.datavideo.index');
        Route::get('/player/datapertandingan', [PlayerDataPertandinganController::class, 'index'])->name('player.datapertandingan.index');
        Route::get('/player/datapertandingan/match-details/{id}', 
        [PlayerDataPertandinganController::class, 'show']
        )->name('player.datapertandingan.show');
        Route::get('/player/raporperkembangan', [PlayerRaporPerkembanganController::class, 'index'])->name('player.raporperkembangan.index');
        Route::get('/player/raporperkembangan/{rapor}', [PlayerRaporPerkembanganController::class, 'show'])
        ->name('player.raporperkembangan.show');

    });
});
