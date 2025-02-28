@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Import Mahasiswa</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('mahasiswa.index') }}" class="{{ request()->routeIs('mahasiswa.index') ? 'active' : '' }}">
                            Mahasiswa Management
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('mahasiswa.show-import') }}" class="{{ request()->routeIs('mahasiswa.show-import') ? 'active' : '' }}">
                            Import Mahasiswa
                        </a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Mahasiswa Import Form -->
                <form method="POST" action="{{ route('mahasiswa.import') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Jurusan Dropdown -->
                            <div class="form-group">
                                <label for="jurusan_id">Jurusan: <span class="text-danger">*</span></label>
                                <select name="jurusan_id" id="jurusan_id"
                                    class="form-control @error('jurusan_id') is-invalid @enderror">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($getJurusan as $jurusan)
                                        <option value="{{ $jurusan->id }}"
                                            {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                            {{ $jurusan->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Prodi Dropdown (Dynamically Loaded based on Jurusan Selection) -->
                            <div class="form-group">
                                <label for="prodis_id">Program Studi: <span class="text-danger">*</span></label>
                                <select name="prodis_id" id="prodis_id"
                                    class="form-control @error('prodis_id') is-invalid @enderror">
                                    <option value="">Pilih Program Studi</option>
                                </select>
                                @error('prodis_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="form-group">
                                <label for="file">File Import Mahasiswa: <span class="text-danger">*</span></label>
                                <input type="file" name="file" id="file"
                                    class="form-control @error('file') is-invalid @enderror">
                                @error('file')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('mahasiswa.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
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
    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 10000,
                timerProgressBar: true,
            });
        @endif

        // Fetch Prodi based on Jurusan selection
        document.getElementById('jurusan_id').addEventListener('change', function () {
            var jurusanId = this.value;
            if (jurusanId) {
                fetch(`/user-management/mahasiswa/getprodi/${jurusanId}`)
                    .then(response => response.json())
                    .then(data => {
                        var prodiSelect = document.getElementById('prodis_id');
                        prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';
                        data.forEach(function (prodi) {
                            prodiSelect.innerHTML += `<option value="${prodi.id}">${prodi.nama_prodi}</option>`;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                document.getElementById('prodis_id').innerHTML = '<option value="">Pilih Program Studi</option>';
            }
        });
    </script>
@endpush
