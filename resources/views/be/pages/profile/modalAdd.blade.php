<div class="modal modal-blur fade" id="modalAdd" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('be/profile.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Company Name -->
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label for="company_name" class="form-label"> Nama Perusahaan / Brand</label>
                                <input type="text" name="company_name" id="company_name"
                                    class="form-control @error('company_name') is-invalid @enderror"
                                    placeholder="Masukkan Nama Perusahaan / Brand" value="{{ old('company_name') }}"
                                    required>
                                @error('company_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Maps Location -->
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label for="maps" class="form-label">Maps </label>
                                <input type="text" name="maps" id="maps"
                                    class="form-control @error('maps') is-invalid @enderror"
                                    placeholder="Masukkan link maps" value="{{ old('maps') }}">
                                @error('maps')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-sm-4 col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" name="address" id="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Masukkan Alamat" value="{{ old('address') }}">
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>



                        <!-- Phone Number -->
                        <div class="col-sm-4 col-md-6">
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Nomor Telephone </label>
                                <input type="number" name="phone_number" id="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    placeholder="Masukkan Nomor Telephone" value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-sm-4 col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Masukkan Email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div class="col-sm-4 col-md-4">
                            <div class="mb-3">
                                <label for="instagram_link" class="form-label">Username Instagram</label>
                                <input type="instagram_link" name="instagram_link" id="instagram_link"
                                    class="form-control @error('instagram_link') is-invalid @enderror"
                                    placeholder="Masukkan Username Instagram " value="{{ old('instagram_link') }}">
                                @error('instagram_link')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="col-sm-4 col-md-4">
                            <div class="mb-3">
                                <label for="whatsapp_link" class="form-label">Whatsapp</label>
                                <input type="number" name="whatsapp_link" id="whatsapp_link"
                                    class="form-control @error('whatsapp_link') is-invalid @enderror"
                                    placeholder="Masukkan  No whatsapp ex.62821 " value="{{ old('whatsapp_link') }}">
                                @error('whatsapp_link')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi / Tentang</label>
                                <textarea name="description" id="description" rows="8"
                                    class="form-control @error('description') is-invalid @enderror" placeholder="Enter Description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Logo -->
                        <div class="col-sm-6 col-md-4">
                            <div class="mb-3">
                                <label for="logo_path" class="form-label">Logo</label>
                                <input type="file" name="logo_path" id="logo_path"
                                    class="form-control @error('logo_path') is-invalid @enderror" accept="image/*">
                                @error('logo_path')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- Image Preview -->
                            <div class="mb-3">
                                <img id="logoPreview" class="img-fluid border" alt="Logo Preview"
                                    style="display: none; max-height: 120px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="ti ti-plus icon"></i>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('logo_path').addEventListener('change', function(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Tampilkan gambar
            };
            reader.readAsDataURL(input.files[0]); // Baca file sebagai URL data
        } else {
            preview.src = '';
            preview.style.display = 'none'; // Sembunyikan gambar jika tidak ada file
        }
    });
</script>
