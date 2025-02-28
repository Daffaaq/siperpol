@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Mahasiswa Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('mahasiswa.index') }}"
                            class="{{ request()->routeIs('mahasiswa.index') ? 'active' : '' }}">Mahasiswa Management</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <!-- Dropdown Filters (Taruh di kiri) -->
                    <div class="d-flex">
                        <select id="jurusanFilter" class="form-control form-control-sm mr-2">
                            <option value="">Select Jurusan</option>
                            <!-- Jurusan options will be added dynamically -->
                        </select>
                        <select id="prodiFilter" class="form-control form-control-sm">
                            <option value="">Select Prodi</option>
                            <!-- Prodi options will be added dynamically -->
                        </select>
                    </div>

                    <!-- Action Buttons (Taruh di kanan) -->
                    <div class="d-flex">
                        <a href="{{ route('mahasiswa.show-import') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                            <i class="fas fa-upload fa-sm text-white-50"></i> Mahasiswa Import
                        </a>
                        <a href="{{ route('mahasiswa.create') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Mahasiswa
                        </a>
                    </div>
                </div>


                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="MahasiswaTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Prodi</th>
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
            // Load Jurusan options
            $.ajax({
                url: '{{ route('mahasiswa.getJurusan') }}', // Pastikan ini sesuai dengan route yang benar
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var jurusanSelect = $('#jurusanFilter');
                    jurusanSelect.empty();
                    jurusanSelect.append('<option value="">Select Jurusan</option>');
                    $.each(response, function(index, jurusan) {
                        jurusanSelect.append('<option value="' + jurusan.id + '">' + jurusan
                            .nama_jurusan + '</option>');
                    });
                }
            });


            // Jurusan Filter Change
            $('#jurusanFilter').on('change', function() {
                var jurusanId = $(this).val();
                var prodiSelect = $('#prodiFilter');
                prodiSelect.empty();
                prodiSelect.append('<option value="">Select Prodi</option>');

                if (jurusanId) {
                    // Fetch Prodi based on selected Jurusan
                    $.ajax({
                        url: '{{ route('mahasiswa.getProdi', ':jurusanId') }}'.replace(':jurusanId',
                            jurusanId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            $.each(response, function(index, prodi) {
                                prodiSelect.append('<option value="' + prodi.id + '">' +
                                    prodi.nama_prodi + '</option>');
                            });
                        }
                    });

                }
            });

            // DataTable Initialization with Filters
            var dataMaster = $('#MahasiswaTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('mahasiswa.list') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}'; // Mengirimkan token CSRF
                        d.prodi_id = $('#prodiFilter').val(); // Mendapatkan prodi_id yang dipilih
                        d.jurusan_id = $('#jurusanFilter').val(); // Mendapatkan jurusan_id yang dipilih
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_mahasiswa',
                        name: 'nama_mahasiswa'
                    },
                    {
                        data: 'nama_prodi',
                        name: 'nama_prodi'
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
                            let showUrl = `/user-management/mahasiswa/${data}`;
                            let editUrl = `/user-management/mahasiswa/${data}/edit`;

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
            $('#jurusanFilter, #prodiFilter').on('change', function() {
                dataMaster.draw(); // Force DataTable to reload data
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
                    const url = `/user-management/mahasiswa/${id}`;
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
                                $('#MahasiswaTables').DataTable().ajax.reload();
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
