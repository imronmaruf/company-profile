@extends('be.layouts.main')

@push('title')
    Preview Hero
@endpush

@push('css')
    <style>
        /* Set the height of carousel images to 60% of the carousel container */
        .carousel-inner img {
            width: 100%;
            /* Full width */
            height: 100%;
            /* Set image height to 100% of the carousel container height */
            object-fit: contain;
            /* Ensures aspect ratio is preserved */
        }
    </style>
@endpush

@push('pageHeader')
    {{-- <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Preview Konten Hero Landing Page
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('be/hero.create') }}" class="btn btn-secondary d-none d-sm-inline-block">
                            <i class="ti ti-arrow-left icon"></i>
                            Kembali
                        </a>
                        <a href="{{ route('be/hero.create') }}" class="btn btn-secondary d-sm-none btn-icon">
                            <i class="ti ti-arrow-left icon"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endpush

@section('content')
    <div class="container-xl mt-3">
        <div id="hero-container">
            <div class="card duplicate-container mb-2">
                <!-- Card Status Top -->
                <div class="card-status-top bg-green"></div>

                <!-- Card Header -->
                <div class="card-header">
                    <h3 class="card-title"><strong>Preview Konten Hero Landing Page</strong></h3>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('be/hero.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
                                <i class="ti ti-arrow-left icon"></i>
                                Kembali
                            </a>
                            <a href="{{ route('be/hero.index') }}" class="btn btn-secondary d-sm-none btn-icon">
                                <i class="ti ti-arrow-left icon"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div id="carousel-indicators" class="carousel slide" data-bs-ride="carousel">
                        <!-- Carousel Indicators -->
                        <div class="carousel-indicators">
                            @foreach ($dataHero as $key => $data)
                                <button type="button" data-bs-target="#carousel-indicators"
                                    data-bs-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"
                                    aria-current="{{ $key === 0 ? 'true' : 'false' }}"></button>
                            @endforeach
                        </div>

                        <!-- Carousel Items -->
                        <div class="carousel-inner">
                            @foreach ($dataHero as $key => $data)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img class="d-block w-100" alt="Hero Image"
                                        src="{{ asset('storage/' . $data->hero_image) }}">
                                </div>
                            @endforeach

                            <div class="carousel-caption-background d-none d-md-block"></div>
                            <div class="carousel-caption d-none d-md-block">
                                <h3>{{ $data->title_hero }}</h3>
                                <p>{{ $data->description }}</p>
                            </div>
                        </div>

                        <!-- Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-indicators"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel-indicators"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
