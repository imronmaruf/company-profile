<!-- Modal Tambah Tim -->
<div class="modal modal-blur fade" id="modalAdd" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('be/teams.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Posisi/Jabatan</label>
                                <input type="text" id="position" name="position"
                                    class="form-control @error('position') is-invalid @enderror"
                                    value="{{ old('position') }}">
                                @error('position')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Foto</label>
                                <input type="file" id="photo_path" name="photo_path"
                                    class="form-control @error('photo_path') is-invalid @enderror"
                                    onchange="previewImage(this, 'previewImageAdd')" accept="image/*">
                                @error('photo_path')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <div class="image-preview-container mt-2">
                                    <img id="previewImageAdd" class="preview-image" style="display: none;">
                                </div>
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

<!-- Modal Edit Tim -->
@foreach ($dataTeams as $data)
    <div class="modal modal-blur fade" id="modalEdit{{ $data->id }}" tabindex="-1" role="dialog"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('be/teams.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $data->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Posisi/Jabatan</label>
                                    <input type="text" name="position"
                                        class="form-control @error('position') is-invalid @enderror"
                                        value="{{ old('position', $data->position) }}" required>
                                    @error('position')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Foto</label>
                                    <input type="file" name="photo_path"
                                        class="form-control @error('photo_path') is-invalid @enderror"
                                        onchange="previewImage(this, 'previewImageEdit{{ $data->id }}')"
                                        accept="image/*">
                                    @error('photo_path')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <div class="image-preview-container mt-2">
                                        <img id="previewImageEdit{{ $data->id }}" class="preview-image"
                                            src="{{ $data->photo_path ? asset('storage/' . $data->photo_path) : '' }}"
                                            style="display: {{ $data->photo_path ? 'block' : 'none' }};">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ms-auto gap-1">
                            <i class="ti ti-check"></i> Simpan
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
            // Jika tidak ada file yang dipilih di modal tambah, sembunyikan preview
            if (previewId === 'previewImageAdd') {
                preview.src = '';
                preview.style.display = 'none';
            }
            // Untuk modal edit, biarkan gambar yang ada jika tidak ada file baru yang dipilih
        }
    }

    // Reset preview image saat modal tambah ditutup
    document.getElementById('modalAdd').addEventListener('hidden.bs.modal', function() {
        document.getElementById('photo_path').value = '';
        document.getElementById('previewImageAdd').src = '';
        document.getElementById('previewImageAdd').style.display = 'none';
    });
</script>
