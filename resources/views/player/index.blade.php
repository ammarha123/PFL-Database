@extends('layout.admin')

@section('content')
    <div class="container mt-4">
        <div class="row">
            @include('partial.player-data')
            <div class="row mt-5">
                <!-- Training Data -->
                <div class="col-md-4 mb-3">
                    <a href="{{ route("player.datalatihan.index") }}" class="text-decoration-none">
                        <div class="card menu">
                            <div class="d-flex align-items-center p-3">
                                <img src="{{ asset('img/icon1.png') }}" class="card-img-left me-3" alt="Data Latihan">
                                <div>
                                    <h5 class="card-title mb-2">Data Latihan</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Match Data -->
                <div class="col-md-4 mb-3">
                    <a href="{{ route("player.datapertandingan.index") }}" class="text-decoration-none">
                        <div class="card menu">
                            <div class="d-flex align-items-center p-3">
                                <img src="{{ asset('img/icon2.png') }}" class="card-img-left me-3" alt="Data Pertandingan">
                            <div>
                                <h5 class="card-title mb-2">Data Pertandingan</h5>
                            </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Player Data -->
                <div class="col-md-4 mb-3">
                    <a href="{{ route("evaluasimandiri.index") }}" class="text-decoration-none">
                    <div class="card menu">
                        <div class="d-flex align-items-center p-3">
                            <img src="{{ asset('img/icon3.png') }}" class="card-img-left me-3" alt="Evaluasi Mandiri">
                            <div>
                                <h5 class="card-title mb-2">Evaluasi Mandiri</h5>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Progress Reports -->
                <div class="col-md-4">
                    <a href="{{ route("player.raporperkembangan.index") }}" class="text-decoration-none">
                        <div class="card menu">
                            <div class="d-flex align-items-center p-3">
                                <img src="{{ asset('img/icon4.png') }}" class="card-img-left me-3" alt="Rapor Perkembangan">
                                <div>
                                    <h5 class="card-title mb-2">Rapor<br>Perkembangan</h5>
                                </div>
                            </div>
                        </div>
                        </a>
                </div>

                <!-- Video Data -->
                <div class="col-md-4">
                    <div class="card menu">
                        <a href="{{ route("player.datavideo.index") }}" class="text-decoration-none">
                            <div class="card menu">
                                <div class="d-flex align-items-center p-3">
                                    <img src="{{ asset('img/icon3.png') }}" class="card-img-left me-3" alt="Data Video">
                                    <div>
                                        <h5 class="card-title mb-2">Data Video</h5>
                                    </div>
                                </div>
                            </div>
                            </a>
                    </div>
                </div>

                <!-- Test Data -->
                <div class="col-md-4 mb-3">
                    <a href="{{ route("player.datates.index") }}" class="text-decoration-none">
                        <div class="card menu">
                            <div class="d-flex align-items-center p-3">
                                <img src="{{ asset('img/icon6.png') }}" class="card-img-left me-3" alt="Data Tes">
                                <div>
                                    <h5 class="card-title mb-2">Data Tes</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endsection
