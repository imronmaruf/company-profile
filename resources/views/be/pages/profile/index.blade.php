@extends('be.layouts.main')

@push('title')
    Pengaturan Profile
@endpush

@push('css')
    <style>
        iframe {
            width: 100%;
            height: 300px;
            border: none;
        }
    </style>
@endpush

@push('pageHeader')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Pengaturan Profil Perusahaan
                    </h2>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
    <div class="container-xl mt-3">
        @if (!empty($profile))
            <!-- Jika ada data -->
            <div class="card">
                <!-- Card Status Top -->
                <div class="card-status-top bg-green"></div>
                <!-- Card Header dengan Navigasi -->
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('be/profile.index') }}">Profil</a>
                        </li>
                        <li class="nav-item">
                            <button id="editProfileLink" class="nav-link">
                                <i class="ti ti-pencil icon"></i>
                                Edit
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Konten Card -->
                <div class="row g-3 p-2 d-flex justify-content-center">
                    <!-- Gambar Profil -->
                    <div class="col-4 col-md-2">
                        <div class="card rounded-3 h-100 p-0">
                            <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="Profile"
                                class="card-img rounded-3 object-fit-cover w-100 h-100">

                        </div>
                    </div>

                    <!-- Informasi Profil -->
                    <div class="col-12 col-md-10">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title mb-3">
                                    <h1 class="mb-0">{{ $profile->company_name }}</h1>
                                </div>
                                <div class="d-flex flex-row gap-4">
                                    <!-- Kolom Kiri -->
                                    <div class="d-flex flex-column gap-2 me-5">
                                        <div>
                                            <i class="ti ti-map-pin icon me-2 text-secondary"></i>
                                            Alamat: <strong>{{ $profile->address }}</strong>
                                        </div>
                                        <div>
                                            <i class="ti ti-phone icon me-2 text-secondary"></i>
                                            No. Telepon: <strong>{{ $profile->phone_number }}</strong>
                                        </div>
                                        <div>
                                            <i class="ti ti-mail icon me-2 text-secondary"></i>
                                            Email: <strong>{{ $profile->email }}</strong>
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="d-flex flex-column gap-2">
                                        <div>
                                            <i class="ti ti-brand-instagram icon me-2 text-secondary"></i>
                                            Instagram:
                                            <a href="https://www.instagram.com/{{ $profile->instagram_link }}/"
                                                target="_blank"><strong> {{ $profile->instagram_link }}</strong></a>
                                        </div>
                                        <div>
                                            <i class="ti ti-brand-whatsapp icon me-2 text-secondary"></i>
                                            WhatsApp:
                                            <a href="https://wa.me/{{ $profile->whatsapp_link }}" target="_blank">
                                                <strong>{{ $profile->whatsapp_link }}</strong>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

                <!-- Deskripsi Profil -->
                <div class="card-body">
                    <div class="col">
                        <h3 class="fw-bold">Deskripsi</h3>
                        <div class="my-2">
                            {{ $profile->description }}
                        </div>
                    </div>
                </div>
                <div class="row g-3 p-2 d-flex justify-content-center">
                    <div class="col-12 col-md-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="fw-bold">Maps {{ $profile->company_name }}</h3>
                                <div class="d-flex flex-column gap-2">
                                    <div>
                                        {!! $profile->maps !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Jika tidak ada data -->
            <div class="card ">
                <div class="card-status-top bg-danger"></div>
                <div class="container-xl d-flex flex-column justify-content-center">
                    <div class="empty">
                        <!-- Gambar Ilustrasi -->
                        <div class="empty-img">
                            <img src="{{ asset('be/static/illustrations/undraw_no_data_re_kwbl.svg') }}"
                                style="width: 200px; height: auto;" alt="No Data">
                        </div>

                        <!-- Pesan Tidak Ada Data -->
                        <p class="empty-title mt-0">Belum ada data profile</p>
                        <p class="empty-subtitle text-secondary">
                            Harap tambahkan data terlebih dahulu
                        </p>

                        <!-- Tombol Tambah Data -->
                        <div class="empty-action mt-3">
                            <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                data-bs-target="#modalAdd">
                                <i class="ti ti-plus icon"></i>
                                Tambah Data Profil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @include('be.pages.profile.modalAdd')
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Dropzone("#dropzone-custom")
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('editProfileLink').addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah navigasi langsung

                Swal2.fire({
                    title: 'Konfirmasi Edit Profil',
                    text: 'Apakah Anda yakin ingin mengedit profil?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false, // Menghindari penutupan dengan klik di luar
                    allowEscapeKey: false // Menghindari penutupan dengan tombol Esc
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('be/profile.edit') }}";
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            let successMessage = '{{ session('success') }}';
            if (successMessage) {
                Swal2.fire({
                    icon: "success",
                    title: "Success!",
                    text: successMessage,
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            @if ($errors->any())
                Swal2.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan",
                    html: `
                  @foreach ($errors->all() as $error)
                      <p>{{ $error }}</p>
                  @endforeach
              `,
                    showConfirmButton: true,
                }).then(() => {
                    window.location.reload();
                });
            @endif
        });
    </script>
@endpush
