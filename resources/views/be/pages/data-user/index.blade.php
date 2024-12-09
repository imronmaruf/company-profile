@extends('be.layouts.main')

@push('title')
    Dashboard
@endpush

@push('css')
@endpush

@push('pageHeader')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        Combo layout
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">

                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modalAdd">
                            <i class="ti ti-plus"></i>
                            Create new report
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="container-xl mt-4">
        <div class="card">
            <div class="card-body">
                <div id="table-default"class="table-responsive">
                    <table id="dataTable" class="table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataUser as $data)
                                <tr>
                                    <td class="col-no">{{ $loop->iteration }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td class="col-action">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Delete Button -->
                                            <form action="{{ route('data-user.destroy', $data->id) }}" method="POST"
                                                id="deleteForm{{ $data->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger d-none d-sm-inline-block"
                                                    onclick="confirmDelete('{{ $data->id }}')">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>

                                            <!-- EDIT Button -->
                                            <button type="button" class="btn btn-warning d-none d-sm-inline-block"
                                                data-bs-toggle="modal" data-bs-target="#modalEdit{{ $data->id }}">
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
    @include('be.pages.data-user.modal')
@endsection

@push('js')
    <script>
        function confirmDelete(id) {
            let form = document.getElementById('deleteForm' + id);
            Swal.fire({
                title: 'Apakah anda Yakin?',
                text: "Data akan dihapus secara permanen",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            let errorMessage = '{{ session('error') }}';
            if (errorMessage !== '') {
                Swal.fire({
                    icon: "error",
                    title: "Ooops!",
                    text: errorMessage,
                    showConfirmButton: true,
                });
            }
        }

        let successMessage = @json(session('success'));
        if (successMessage !== '') {
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: successMessage,
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>
@endpush
