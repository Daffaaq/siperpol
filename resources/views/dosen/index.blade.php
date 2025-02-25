@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Dosen Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dosen.index') }}"
                            class="{{ request()->routeIs('dosen.index') ? 'active' : '' }}">Dosen Management</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex">
                        <select id="jurusanFilter" class="form-control" style="width: 200px;">
                            <option value="">-- Select Jurusan --</option>
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <a href="{{ route('dosen.show-import') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-upload fa-sm text-white-50"></i> Dosen Import
                        </a>
                        <a href="{{ route('dosen.create') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Dosen
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="DosenTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Jurusan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will fill this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item {
            font-size: 0.875rem;
        }

        .breadcrumb-item a {
            color: #464646;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .breadcrumb-item a.active {
            font-weight: bold;
            color: #007bff;
            pointer-events: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            var dataMaster = $('#DosenTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('dosen.list') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: function(d) {
                        d.jurusan_id = $('#jurusanFilter').val(); // Mengirimkan jurusan_id ke server
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_dosen',
                        name: 'nama_dosen'
                    },
                    {
                        data: 'email_dosen',
                        name: 'email_dosen'
                    },
                    {
                        data: 'jenis_kelamin_dosen',
                        name: 'jenis_kelamin_dosen',
                        render: function(data) {
                            return data == 'L' ? 'Laki-laki' : 'Perempuan';
                        }
                    },
                    {
                        data: 'nama_jurusan',
                        name: 'nama_jurusan'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            let showUrl = `/user-management/dosen/${data}`;
                            let editUrl = `/user-management/dosen/${data}/edit`;

                            return `
                        <a href="${showUrl}" class="btn icon btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="${editUrl}" class="btn icon btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button class="btn icon btn-sm btn-danger" onclick="confirmDelete('${data}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                        }
                    }
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
            });

            // Apply filter when a new jurusan is selected
            $('#jurusanFilter').change(function() {
                dataMaster.ajax.reload();
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            @endif
        });


        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = `/user-management/dosen/${id}`;
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dihapus!',
                                    text: response.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                $('#DosenTables').DataTable().ajax.reload();
                                window.location.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan.',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Tidak dapat menghubungi server.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
