@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Organisasi Intra Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('organisasi.index') }}"
                            class="{{ request()->routeIs('organisasi.index') ? 'active' : '' }}">Organisasi Intra
                            Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('organisasi.edit', $organisasi->id) }}"
                            class="{{ request()->routeIs('organisasi.edit') ? 'active' : '' }}">Edit Organisasi Intra</a>
                    </li>
                </ol>
            </div>

            {{-- show error validation --}}
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach

            <div class="card-body">
                <!-- Organisasi Intra Edit Form -->
                <form method="POST" action="{{ route('organisasi.update', $organisasi->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nama Organisasi Intra -->
                    <div class="form-group">
                        <label for="nama_organisasi_intra">Nama Organisasi Intra:</label>
                        <input type="text" name="nama_organisasi_intra" id="nama_organisasi_intra"
                            placeholder="Nama Organisasi Intra"
                            class="form-control @error('nama_organisasi_intra') is-invalid @enderror"
                            value="{{ old('nama_organisasi_intra', $organisasi->nama_organisasi_intra) }}">
                        @error('nama_organisasi_intra')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Kode Organisasi Intra -->
                    <div class="form-group">
                        <label for="kode_organisasi_intra">Kode Organisasi Intra:</label>
                        <input type="text" name="kode_organisasi_intra" id="kode_organisasi_intra"
                            placeholder="Kode Organisasi Intra"
                            class="form-control @error('kode_organisasi_intra') is-invalid @enderror"
                            value="{{ old('kode_organisasi_intra', $organisasi->kode_organisasi_intra) }}">
                        @error('kode_organisasi_intra')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tipe Organisasi Intra -->
                    <div class="form-group">
                        <label for="tipe_organisasi_intra">Tipe Organisasi Intra:</label>
                        <select name="tipe_organisasi_intra" id="tipe_organisasi_intra"
                            class="form-control @error('tipe_organisasi_intra') is-invalid @enderror">
                            <option value="jurusan" {{ old('tipe_organisasi_intra', $organisasi->tipe_organisasi_intra) == 'jurusan' ? 'selected' : '' }}>
                                Jurusan</option>
                            <option value="non-jurusan"
                                {{ old('tipe_organisasi_intra', $organisasi->tipe_organisasi_intra) == 'non-jurusan' ? 'selected' : '' }}>
                                Non-Jurusan</option>
                            <option value="lembaga-tinggi"
                                {{ old('tipe_organisasi_intra', $organisasi->tipe_organisasi_intra) == 'lembaga-tinggi' ? 'selected' : '' }}>
                                Lembaga Tinggi</option>
                        </select>
                        @error('tipe_organisasi_intra')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Jurusan -->
                    <div class="form-group" id="jurusan-group" style="{{ $organisasi->tipe_organisasi_intra == 'jurusan' ? 'display:block;' : 'display:none;' }}">
                        <label for="jurusans_id">Jurusan:</label>
                        <select name="jurusans_id" id="jurusans_id"
                            class="form-control @error('jurusans_id') is-invalid @enderror">
                            <option value="">-- Select Jurusan --</option>
                            @foreach ($getJurusan as $jurusan)
                                <option value="{{ $jurusan->id }}"
                                    {{ old('jurusans_id', $organisasi->jurusans_id) == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}</option>
                            @endforeach
                        </select>
                        @error('jurusans_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="form-group">
                        <label for="is_active">Is Active:</label><br>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="is_active" id="active" value="1"
                                class="form-check-input @error('is_active') is-invalid @enderror"
                                {{ old('is_active', $organisasi->is_active) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="is_active" id="inactive" value="0"
                                class="form-check-input @error('is_active') is-invalid @enderror"
                                {{ old('is_active', $organisasi->is_active) == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="inactive">Inactive</label>
                        </div>
                        @error('is_active')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Ketua Umum Information -->
                    <h5 class="mt-4">Ketua Umum</h5>

                    <!-- Nama Ketua Umum -->
                    <div class="form-group">
                        <label for="nama_ketua_umum">Nama Ketua Umum:</label>
                        <input type="text" name="nama_ketua_umum" id="nama_ketua_umum" placeholder="Nama Ketua Umum"
                            class="form-control @error('nama_ketua_umum') is-invalid @enderror"
                            value="{{ old('nama_ketua_umum', $organisasi->nama_ketua_umum) }}">
                        @error('nama_ketua_umum')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Ketua Umum -->
                    <div class="form-group">
                        <label for="email_ketua_umum">Email Ketua Umum:</label>
                        <input type="email" name="email_ketua_umum" id="email_ketua_umum" placeholder="Email Ketua Umum"
                            class="form-control @error('email_ketua_umum') is-invalid @enderror"
                            value="{{ old('email_ketua_umum', $organisasi->email_ketua_umum) }}">
                        @error('email_ketua_umum')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Ketua Umum -->
                    <div class="form-group">
                        <label for="password_ketua_umum">Password Ketua Umum (Leave blank to keep the same):</label>
                        <input type="password" name="password_ketua_umum" id="password_ketua_umum"
                            placeholder="Password Ketua Umum"
                            class="form-control @error('password_ketua_umum') is-invalid @enderror"
                            value="{{ old('password_ketua_umum') }}">
                        @error('password_ketua_umum')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('organisasi.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
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
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Check initial value of the tipe_organisasi_intra select field
            toggleJurusanField();

            // Listen for changes on the tipe_organisasi_intra select field
            $('#tipe_organisasi_intra').change(function() {
                toggleJurusanField();
            });

            // Function to toggle the visibility of the jurusan field based on the selected tipe_organisasi_intra
            function toggleJurusanField() {
                var tipeOrganisasiIntra = $('#tipe_organisasi_intra').val();
                if (tipeOrganisasiIntra == 'jurusan') {
                    $('#jurusan-group').show(); // Show the jurusan dropdown
                } else {
                    $('#jurusan-group').hide(); // Hide the jurusan dropdown
                    $('#jurusans_id').val(''); // Clear the selected value
                }
            }
        });
    </script>
@endpush
