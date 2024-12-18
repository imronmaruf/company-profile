@extends('be.layouts.main')

@push('title')
    Hero Konten
@endpush

@push('css')
    <style>
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
        }

        .delete-btn-container {
            display: none;
        }

        .delete-btn-container.active {
            display: block;
        }
    </style>
@endpush

@push('pageHeader')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Tambah Konten Hero
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <!-- for desktop -->
                        <a href="{{ route('be/hero.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
                            <i class="ti ti-arrow-left icon"></i>
                            Kembali
                        </a>
                        <!-- for mobile -->
                        <a href="{{ route('be/hero.index') }}" class="btn btn-secondary d-sm-none btn-icon">
                            <i class="ti ti-arrow-left icon"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="container-xl mt-3">
        <form action="{{ route('be/hero.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="hero-container">
                <div class="card duplicate-container mb-2">
                    <!-- Card Status Top -->
                    <div class="card-status-top bg-green"></div>
                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between w-100">
                            <h3 class="card-title mb-0">Tambah Data Hero</h3>
                            <div class="btn-list d-flex align-items-center">
                                <button type="button" class="btn btn-danger delete-btn-container"
                                    onclick="deleteCard(this)">
                                    <i class=" ti ti-trash icon"></i> Hapus
                                </button>
                                <!-- Duplicate Button -->
                                <button type="button" class="btn btn-success duplicate-btn" onclick="duplicateCard(this)">
                                    <i class="ti ti-copy icon"></i> Duplikat
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">Judul Hero</label>
                                    <input type="text" name="title_hero[]"
                                        class="form-control @error('title_hero.*') is-invalid @enderror"
                                        value="{{ old('title_hero.0') }}" placeholder="Masukkan Judul Hero">
                                    @error('title_hero.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Gambar Hero</label>
                                    <input type="file" name="hero_image[]"
                                        class="form-control @error('hero_image.*') is-invalid @enderror"
                                        onchange="previewImage(this)">
                                    @error('hero_image.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="image-preview-container">
                                        <img class="preview-image" src="" alt="Image Preview"
                                            style="display: none;">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-2 h-100">
                                    <label class="form-label">Deskripsi Hero</label>
                                    <textarea name="description[]" class="form-control @error('description.*') is-invalid @enderror" rows="5"
                                        placeholder="Masukkan Deskripsi Hero">{{ old('description.0') }}</textarea>
                                    @error('description.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy icon"></i> Simpan Semua Data
                </button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        // Preview gambar yang diunggah
        function previewImage(input) {
            const previewContainer = input.closest('.card').querySelector('.image-preview-container');
            const previewImage = previewContainer.querySelector('.preview-image');

            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block'; // Tampilkan gambar
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                previewImage.style.display = 'none'; // Sembunyikan jika tidak ada gambar
            }
        }

        // Fungsi untuk menduplikasi card
        function duplicateCard(button) {
            const originalCard = button.closest('.card.duplicate-container');
            const duplicateCard = originalCard.cloneNode(true);
            const heroContainer = document.getElementById('hero-container');
            const newHeroNumber = heroContainer.querySelectorAll('.card.duplicate-container').length + 1;

            duplicateCard.querySelector('.card-title').textContent = `Tambah Data Hero ${newHeroNumber}`;
            duplicateCard.querySelectorAll('input, textarea').forEach(input => {
                input.value = '';
                input.classList.remove('is-invalid');
            });

            duplicateCard.querySelectorAll('.invalid-feedback').forEach(msg => msg.remove());
            duplicateCard.querySelector('.delete-btn-container').classList.add('active'); // Show delete button

            heroContainer.appendChild(duplicateCard);
        }

        // Fungsi untuk menghapus card duplikat
        function deleteCard(button) {
            const card = button.closest('.card.duplicate-container');
            card.remove();
        }
    </script>
@endpush
