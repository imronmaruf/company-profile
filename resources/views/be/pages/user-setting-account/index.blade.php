@extends('be.layouts.main')

@push('title')
    Pengaturan Akun
@endpush

@push('css')
@endpush

@push('pageHeader')
@endpush

@section('content')
    <div class="container-xl mt-3">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <!-- Card Status Top -->
                    <div class="card-status-top bg-green"></div>

                    <!-- Card Header dengan Navigasi -->
                    <div class="card-header">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('be/account/setting.edit') }}">Profil</a>
                            </li>
                            <li class="nav-item">
                                <button id="editAccountLink" class="nav-link">
                                    <i class="ti ti-pencil icon"></i>
                                    Edit
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Konten Card -->
                    <div class="card-body">
                        <div class="card-title mb-3">
                            <h1 class="mb-0">Informasi Akun</h1>
                        </div>
                        <div class="d-flex flex-row gap-4">
                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <i class="ti ti-user icon me-2 text-secondary"></i>
                                    Username:
                                    <strong>{{ $account->name }}</strong>
                                </div>
                                <div>
                                    <i class="ti ti-mail icon me-2 text-secondary"></i>
                                    Email:
                                    <strong>{{ $account->email }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('editAccountLink').addEventListener('click', function(event) {
                event.preventDefault();

                Swal2.fire({
                    title: 'Konfirmasi Edit Akun',
                    text: 'Apakah Anda yakin ingin mengedit akun anda?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('be/account/setting.edit') }}";
                    }
                });
            });

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
