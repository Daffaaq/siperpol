@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Ruang Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('ruang.index') }}"
                            class="{{ request()->routeIs('ruang.index') ? 'active' : '' }}">Ruang Management</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <select id="jurusanFilter" class="form-control" style="width: 200px;">
                        <option value="">Pilih Jurusan</option>
                        @foreach ($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('ruang.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Create New Ruang
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="RuangTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ruang</th>
                                <th>Kode Ruang</th>
                                <th>Jurusan</th>
                                <th>Status</th>
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
    var dataMaster = $('#RuangTables').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('ruang.list') }}',
            type: 'POST',
            dataType: 'json',
            data: function(d) {
                d._token = '{{ csrf_token() }}';
                d.jurusan_id = $('#jurusanFilter').val(); // Mengirimkan filter jurusan
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'nama_ruang',
                name: 'nama_ruang'
            },
            {
                data: 'kode_ruang',
                name: 'kode_ruang'
            },
            {
                data: 'nama_jurusan',
                name: 'nama_jurusan'
            },
            {
                data: 'is_active',
                name: 'is_active',
                render: function(data) {
                    return data == 1 ? 'Active' : 'Inactive';
                }
            },
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    let showUrl = `/master-management/ruang/${data}`;
                    let editUrl = `/master-management/ruang/${data}/edit`;

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

    // Menambahkan event listener untuk dropdown filter jurusan
    $('#jurusanFilter').change(function() {
        dataMaster.ajax.reload(); // Memuat ulang data dengan filter baru
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
                    const url = `/master-management/ruang/${id}`;
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
                                $('#RuangTables').DataTable().ajax.reload();
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
