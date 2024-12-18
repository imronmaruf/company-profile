@foreach ($dataHero as $index => $data)
    <div class="modal modal-blur fade" id="modalEdit{{ $data->id }}" tabindex="-1" role="dialog" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Hero</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('be/hero.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <!-- Title Hero -->
                                <div class="mb-2">
                                    <label class="form-label">Judul Hero</label>
                                    <input type="text" name="title_hero"
                                        class="form-control @error('title_hero') is-invalid @enderror"
                                        value="{{ old('title_hero', $data->title_hero) }}"
                                        placeholder="Masukkan Judul Hero">
                                    @error('title_hero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Hero Image -->
                                <div class="mb-2">
                                    <label class="form-label">Gambar Hero</label>
                                    <input type="file" name="hero_image"
                                        class="form-control @error('hero_image') is-invalid @enderror"
                                        onchange="previewImage(this, 'previewImage{{ $data->id }}')">
                                    @error('hero_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <!-- Preview Image -->
                                    <div class="image-preview-container mt-2">
                                        <img id="previewImage{{ $data->id }}" class="preview-image"
                                            src="{{ $data->hero_image ? asset('storage/' . $data->hero_image) : '' }}"
                                            alt="Image Preview"
                                            style="{{ $data->hero_image ? 'display: block;' : 'display: none;' }} max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <!-- Description -->
                                <div class="mb-2 h-100">
                                    <label class="form-label">Deskripsi Hero</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5"
                                        placeholder="Masukkan Deskripsi Hero">{{ old('description', $data->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="ti ti-checklist icon"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script>
