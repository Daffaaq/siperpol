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
                    <li class="breadcrumb-item">
                        <a href="{{ route('mahasiswa.create') }}"
                            class="{{ request()->routeIs('mahasiswa.create') ? 'active' : '' }}">Create Mahasiswa</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Mahasiswa Create Form -->
                <form method="POST" action="{{ route('mahasiswa.store') }}">
                    @csrf

                    <div class="row">
                        <!-- Left Column (6 fields) -->
                        <div class="col-md-6">
                            <!-- Jurusan -->
                            <div class="form-group">
                                <label for="jurusans_id">Jurusan: <span class="text-danger">*</span></label>
                                <select name="jurusans_id" id="jurusans_id"
                                    class="form-control @error('jurusans_id') is-invalid @enderror">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($getJurusan as $jurusan)
                                        <option value="{{ $jurusan->id }}"
                                            {{ old('jurusans_id') == $jurusan->id ? 'selected' : '' }}>
                                            {{ $jurusan->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusans_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prodi -->
                            <div class="form-group">
                                <label for="prodis_id">Prodi: <span class="text-danger">*</span></label>
                                <select name="prodis_id" id="prodis_id"
                                    class="form-control @error('prodis_id') is-invalid @enderror">
                                    <option value="">Pilih Prodi</option>
                                </select>
                                @error('prodis_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Mahasiswa -->
                            <div class="form-group">
                                <label for="nama_mahasiswa">Nama Mahasiswa: <span class="text-danger">*</span></label>
                                <input type="text" name="nama_mahasiswa" id="nama_mahasiswa" placeholder="Nama Mahasiswa"
                                    class="form-control @error('nama_mahasiswa') is-invalid @enderror"
                                    value="{{ old('nama_mahasiswa') }}">
                                @error('nama_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIM Mahasiswa -->
                            <div class="form-group">
                                <label for="nim_mahasiswa">NIM Mahasiswa: <span class="text-danger">*</span></label>
                                <input type="text" name="nim_mahasiswa" id="nim_mahasiswa" placeholder="NIM Mahasiswa"
                                    class="form-control @error('nim_mahasiswa') is-invalid @enderror"
                                    value="{{ old('nim_mahasiswa') }}">
                                @error('nim_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Mahasiswa -->
                            <div class="form-group">
                                <label for="email_mahasiswa">Email Mahasiswa: <span class="text-danger">*</span></label>
                                <input type="email" name="email_mahasiswa" id="email_mahasiswa"
                                    placeholder="Email Mahasiswa"
                                    class="form-control @error('email_mahasiswa') is-invalid @enderror"
                                    value="{{ old('email_mahasiswa') }}">
                                @error('email_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat Mahasiswa -->
                            <div class="form-group">
                                <label for="alamat_mahasiswa">Alamat Mahasiswa:</label>
                                <input type="text" name="alamat_mahasiswa" id="alamat_mahasiswa"
                                    placeholder="Alamat Mahasiswa"
                                    class="form-control @error('alamat_mahasiswa') is-invalid @enderror"
                                    value="{{ old('alamat_mahasiswa') }}">
                                @error('alamat_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column (6 fields) -->
                        <div class="col-md-6">
                            <!-- Nama Pendek Mahasiswa -->
                            <div class="form-group">
                                <label for="nama_pendek_mahasiswa">Nama Pendek Mahasiswa: <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nama_pendek_mahasiswa" id="nama_pendek_mahasiswa"
                                    placeholder="Nama Pendek Mahasiswa"
                                    class="form-control @error('nama_pendek_mahasiswa') is-invalid @enderror"
                                    value="{{ old('nama_pendek_mahasiswa') }}">
                                @error('nama_pendek_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Mahasiswa -->
                            <div class="form-group">
                                <label for="password_mahasiswa">Password Mahasiswa: <span
                                        class="text-danger">*</span></label>
                                <input type="password" name="password_mahasiswa" id="password_mahasiswa"
                                    placeholder="Password Mahasiswa"
                                    class="form-control @error('password_mahasiswa') is-invalid @enderror"
                                    value="{{ old('password_mahasiswa') }}">
                                @error('password_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin Mahasiswa -->
                            <div class="form-group">
                                <label for="jenis_kelamin_mahasiswa">Jenis Kelamin: <span
                                        class="text-danger">*</span></label>
                                <select name="jenis_kelamin_mahasiswa" id="jenis_kelamin_mahasiswa"
                                    class="form-control @error('jenis_kelamin_mahasiswa') is-invalid @enderror">
                                    <option value="L" {{ old('jenis_kelamin_mahasiswa') == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin_mahasiswa') == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('jenis_kelamin_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir Mahasiswa -->
                            <div class="form-group">
                                <label for="tanggal_lahir_mahasiswa">Tanggal Lahir Mahasiswa: <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir_mahasiswa" id="tanggal_lahir_mahasiswa"
                                    class="form-control @error('tanggal_lahir_mahasiswa') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir_mahasiswa') }}">
                                @error('tanggal_lahir_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- No Telepon Mahasiswa -->
                            <div class="form-group">
                                <label for="no_telepon_mahasiswa">No Telepon Mahasiswa:</label>
                                <input type="text" name="no_telepon_mahasiswa" id="no_telepon_mahasiswa"
                                    placeholder="No Telepon Mahasiswa"
                                    class="form-control @error('no_telepon_mahasiswa') is-invalid @enderror"
                                    value="{{ old('no_telepon_mahasiswa') }}">
                                @error('no_telepon_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('mahasiswa.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
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

        .position-relative {
            position: relative;
        }

        .fas.fa-eye {
            font-size: 20px;
            color: #007bff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Populate Prodi dropdown based on Jurusan selection
        document.getElementById('jurusans_id').addEventListener('change', function() {
            var jurusanId = this.value;
            var prodiDropdown = document.getElementById('prodis_id');

            if (jurusanId) {
                fetch(`/user-management/mahasiswa/getprodi/${jurusanId}`)
                    .then(response => response.json())
                    .then(data => {
                        prodiDropdown.innerHTML = '<option value="">Pilih Prodi</option>';
                        data.forEach(prodi => {
                            prodiDropdown.innerHTML +=
                                `<option value="${prodi.id}">${prodi.nama_prodi}</option>`;
                        });
                    });
            } else {
                prodiDropdown.innerHTML = '<option value="">Pilih Prodi</option>';
            }
        });
    </script>
@endpush
