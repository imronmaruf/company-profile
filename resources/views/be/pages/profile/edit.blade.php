@extends('be.layouts.main')

@push('title')
    Edit Profil
@endpush

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/libs/dropzone/dist/dropzone.css"
        rel="stylesheet" />
@endpush

@section('content')
    <div class="container-xl mt-3">
        <div class="card">
            <!-- Card Status Top -->
            <div class="card-status-top bg-green"></div>

            <!-- Card Header -->
            <div class="card-header">
                <h3 class="card-title">Edit Profil</h3>
            </div>

            <!-- Form -->
            <form action="{{ route('be/profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <!-- Company Name -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Nama Perusahaan/Brand</label>
                                <input type="text" name="company_name"
                                    class="form-control @error('company_name') is-invalid @enderror"
                                    value="{{ old('company_name', $profile->company_name) }}"
                                    placeholder="Enter Company Name">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <input type="text" name="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address', $profile->address) }}" placeholder="Enter Address">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Nomor Telephone</label>
                                <input type="text" name="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    value="{{ old('phone_number', $profile->phone_number) }}"
                                    placeholder="Enter Phone Number">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Email</label>
                                <input type="text" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $profile->email) }}" placeholder="Enter Email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-8">
                            <div class="mb-2">
                                <label class="form-label">Deskripsi / Tentang</label>
                                <textarea name="description" rows="9" class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Write a brief description">{{ old('description', $profile->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Logo Upload -->
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
                                <img id="logoPreview" class="img-fluid border" alt="Logo Preview" style="max-height: 120px;"
                                    src="{{ $profile->logo_path ? asset('storage/' . $profile->logo_path) : '' }}">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('be/profile.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.getElementById('logo_path').addEventListener('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('logoPreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]); // Baca file sebagai URL data
            } else {
                // Reset preview jika tidak ada file yang dipilih
                preview.src = "{{ $profile->logo_path ? asset('storage/' . $profile->logo_path) : '' }}";
            }
        });
    </script>
@endpush
