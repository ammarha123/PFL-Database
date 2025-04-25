@extends('layout.admin')

@section('content')
    <div class="container mt-4">
        <div class="row mt-5">
            <div class="col-md-4 mb-5">
                <a href="{{ route('datalatihan.index') }}" class="text-decoration-none">
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
            <div class="col-md-4 mb-5">
                <a href="{{ route('datapertandingan.index') }}" class="text-decoration-none">
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
            <div class="col-md-4 mb-5">
                <a href="{{ route('datapemain.index') }}" class="text-decoration-none">
                    <div class="card menu">
                        <div class="d-flex align-items-center p-3">
                            <img src="{{ asset('img/icon3.png') }}" class="card-img-left me-3" alt="Data Pemain">
                            <div>
                                <h5 class="card-title mb-2">Data Pemain</h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <a href="#" class="text-decoration-none">
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
            <div class="col-md-4">
                <a href="{{ route('datavideo.index') }}" class="text-decoration-none">
                    <div class="card menu">
                        <div class="d-flex align-items-center p-3">
                            <img src="{{ asset('img/icon5.png') }}" class="card-img-left me-3" alt="Data Video">
                            <div>
                                <h5 class="card-title mb-2">Data Video</h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('datates.index') }}" class="text-decoration-none">
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
