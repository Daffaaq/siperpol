@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Organisasi Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('organisasi.index') }}"
                            class="{{ request()->routeIs('organisasi.index') ? 'active' : '' }}">Organisasi Management</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('organisasi.create') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Create New Organisasi
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="OrganisasiTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Organisasi</th>
                                <th>Kode Organisasi</th>
                                <th>Tipe Organisasi</th>
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
            var dataMaster = $('#OrganisasiTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('organisasi.list') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_organisasi_intra',
                        name: 'nama_organisasi_intra'
                    },
                    {
                        data: 'kode_organisasi_intra',
                        name: 'kode_organisasi_intra'
                    },
                    {
                        data: 'tipe_organisasi_intra',
                        name: 'tipe_organisasi_intra',
                        render: function(data) {
                            // Create a badge with the corresponding color based on the value of 'tipe_organisasi_intra'
                            var badgeClass = '';
                            var badgeText = '';

                            if (data == 'jurusan') {
                                badgeClass =
                                    'badge bg-success text-white'; // Green for 'Jurusan' with white text
                                badgeText = 'Jurusan';
                            } else if (data == 'non-jurusan') {
                                badgeClass =
                                    'badge bg-primary text-white'; // Blue for 'Non-Jurusan' with white text
                                badgeText = 'Non-Jurusan';
                            } else if (data == 'lembaga-tinggi') {
                                badgeClass =
                                    'badge bg-danger text-white'; // Red for 'Lembaga-Tinggi' with white text
                                badgeText = 'Lembaga-Tinggi';
                            }

                            // Return the badge with the appropriate color
                            return '<span class="' + badgeClass + '">' + badgeText + '</span>';
                        }
                    },
                    {
                        data: 'nama_jurusan',
                        name: 'nama_jurusan',
                        render: function(data) {
                            return data == null ? '-' : data;
                        }
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
                        render: function(data, type, row) {
                            let showUrl = `/master-management/organisasi/${data}`;
                            let editUrl = `/master-management/organisasi/${data}/edit`;

                            let userIcon = row.users_id ? '<i class="bi bi-person-fill"></i>' : '';

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
                                ${userIcon}
                            `;
                        }
                    },
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
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
                    const url = `/master-management/organisasi/${id}`;
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
                                $('#OrganisasiTables').DataTable().ajax.reload();
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
