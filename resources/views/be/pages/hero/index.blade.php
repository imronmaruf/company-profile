@extends('be.layouts.main')

@push('title')
    Hero Konten
@endpush

@push('css')
@endpush

@push('pageHeader')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Konten Hero Landing Page
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @if ($dataHero->where('status', 1)->isNotEmpty())
                            <a href="{{ route('be/hero.preview') }}" class="btn btn-teal d-none d-sm-inline-block">
                                <i class="ti ti-eye icon"></i>
                                Preview
                            </a>

                            <a href="{{ route('be/hero.preview') }}" class="btn btn-teal d-sm-none btn-icon">
                                <i class="ti ti-eye icon"></i>
                            </a>
                        @endif



                        <a href="{{ route('be/hero.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <i class="ti ti-plus icon"></i>
                            Tambah Konten Hero
                        </a>
                        <a href="{{ route('be/hero.create') }}" class="btn btn-primary d-sm-none btn-icon">
                            <i class="ti ti-plus icon"></i>
                        </a>
                    </div>

                    {{-- <div class="btn-list">
                        <a href="{{ route('be/hero.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <i class="ti ti-plus"></i>
                            Tambah Konten Hero
                        </a>
                        <a href="{{ route('be/hero.create') }}" class="btn btn-primary d-sm-none btn-icon">
                            <i class="ti ti-plus icon"></i>
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="container-xl mt-4">
        <div class="card">
            <div class="card-body">
                <div id="table-default" class="table-responsive">
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align: center;">No</th>
                                <th style="width: 20%; text-align: center;">Gambar</th>
                                <th style="width: 17%;">Judul</th>
                                <th style="width: 45%;">Deskripsi</th>
                                <th style="width: 3%;">Status</th>
                                <th style="width: 10%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataHero as $data)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        @if ($data->hero_image)
                                            <div style="border: 1px solid #9e9e9e; display: inline-block;" class="rounded">
                                                <img src="{{ asset('storage/' . $data->hero_image) }}" alt="Hero Image"
                                                    style="max-width: 180px; max-height: 120px; object-fit: cover;"
                                                    class="rounded">
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada gambar</span>
                                        @endif
                                    </td>
                                    <td class="text-wrap" style="max-width: 150px;">{{ $data->title_hero }}</td>
                                    <td class="text-wrap" style="max-width: 500px;">{{ $data->description }}</td>
                                    {{-- <td class="text-wrap" style="max-width: 500px;">{{ $data->status }}</td> --}}
                                    <td class="text-center">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input status-toggle"
                                                data-id="{{ $data->id }}" {{ $data->status == 1 ? 'checked' : '' }}>
                                            <span class="status-text">
                                                {{ $data->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">

                                            <form action="{{ route('be/hero.destroy', $data->id) }}" method="POST"
                                                id="deleteForm{{ $data->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger"
                                                    onclick="confirmDelete('{{ $data->id }}')" data-bs-toggle="tooltip"
                                                    title="Hapus Data">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $data->id }}">
                                                <i class="ti ti-ballpen"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('be.pages.hero.modalEdit')
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-toggle').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const heroId = this.getAttribute('data-id');
                    const statusText = this.nextElementSibling;
                    const isChecking = this.checked;

                    // Jika akan menonaktifkan
                    if (!isChecking) {
                        Swal2.fire({
                            title: 'Nonaktifkan Hero?',
                            html: `
                            <p>Apakah Anda yakin ingin menonaktifkan hero ini?</p>
                            <small class="text-danger">Hero yang dinonaktifkan tidak akan ditampilkan di landing page.</small>
                        `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Nonaktifkan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Lanjutkan proses toggle status
                                fetch(`/be/hero/toggle-status/${heroId}`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]')
                                                .getAttribute('content')
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Update visual state
                                            statusText.textContent = data.status ?
                                                'Aktif' : 'Tidak Aktif';

                                            // Show success message
                                            Swal2.fire({
                                                icon: "success",
                                                title: "Berhasil",
                                                text: data.message,
                                                timer: 1500,
                                                showConfirmButton: false
                                            }).then(() => {
                                                // Reload page after success
                                                location.reload();
                                            });
                                        } else {
                                            // Revert checkbox if failed
                                            this.checked = !this.checked;

                                            // Show error message
                                            Swal2.fire({
                                                icon: "error",
                                                title: "Gagal",
                                                text: data.message,
                                                showConfirmButton: true
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        // Revert checkbox
                                        this.checked = !this.checked;

                                        Swal2.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: "Terjadi kesalahan. Silakan coba lagi.",
                                            showConfirmButton: true
                                        });
                                    });
                            } else {
                                // Jika dibatalkan, kembalikan status checkbox
                                this.checked = true;
                            }
                        });
                    } else {
                        // Jika mengaktifkan, lanjutkan proses normal
                        fetch(`/be/hero/toggle-status/${heroId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update visual state
                                    statusText.textContent = data.status ? 'Aktif' :
                                        'Tidak Aktif';

                                    // Show success message
                                    Swal2.fire({
                                        icon: "success",
                                        title: "Berhasil",
                                        text: data.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Reload page after success
                                        location.reload();
                                    });
                                } else {
                                    // Revert checkbox if failed
                                    this.checked = !this.checked;

                                    // Show error message
                                    Swal2.fire({
                                        icon: "error",
                                        title: "Gagal",
                                        text: data.message,
                                        showConfirmButton: true
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // Revert checkbox
                                this.checked = !this.checked;

                                Swal2.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Terjadi kesalahan. Silakan coba lagi.",
                                    showConfirmButton: true
                                });
                            });
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Pesan sukses
            let successMessage = '{{ session('success') }}';
            if (successMessage) {
                Swal2.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: successMessage,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Reload page after success message
                });
            }

            // Pesan error
            @if ($errors->any())
                Swal2.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan",
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: true,
                });
            @endif
        });

        function confirmDelete(id) {
            Swal2.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`deleteForm${id}`).submit();
                }
            });
        }
    </script>
@endpush
