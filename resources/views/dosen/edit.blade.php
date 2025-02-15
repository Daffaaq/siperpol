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
                    <li class="breadcrumb-item">
                        <a href="{{ route('dosen.edit', $dosen->id) }}"
                            class="{{ request()->routeIs('dosen.edit') ? 'active' : '' }}">Edit Dosen</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Dosen Edit Form -->
                <form method="POST" action="{{ route('dosen.update', $dosen->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Left Column (6 fields) -->
                        <div class="col-md-6">
                            <!-- Nama Dosen -->
                            <div class="form-group">
                                <label for="nama_dosen">Nama Dosen: <span class="text-danger">*</span></label>
                                <input type="text" name="nama_dosen" id="nama_dosen" placeholder="Nama Dosen"
                                    class="form-control @error('nama_dosen') is-invalid @enderror"
                                    value="{{ old('nama_dosen', $dosen->nama_dosen) }}">
                                @error('nama_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- NIP Dosen -->
                            <div class="form-group">
                                <label for="nip_dosen">NIP Dosen:</label>
                                <input type="text" name="nip_dosen" id="nip_dosen" placeholder="NIP Dosen"
                                    class="form-control @error('nip_dosen') is-invalid @enderror"
                                    value="{{ old('nip_dosen', $dosen->nip_dosen) }}">
                                @error('nip_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- NIDN Dosen -->
                            <div class="form-group">
                                <label for="nidn_dosen">NIDN Dosen:</label>
                                <input type="text" name="nidn_dosen" id="nidn_dosen" placeholder="NIDN Dosen"
                                    class="form-control @error('nidn_dosen') is-invalid @enderror"
                                    value="{{ old('nidn_dosen', $dosen->nidn_dosen) }}">
                                @error('nidn_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email Dosen -->
                            <div class="form-group">
                                <label for="email_dosen">Email Dosen: <span class="text-danger">*</span></label>
                                <input type="email" name="email_dosen" id="email_dosen" placeholder="Email Dosen"
                                    class="form-control @error('email_dosen') is-invalid @enderror"
                                    value="{{ old('email_dosen', $dosen->email_dosen) }}">
                                @error('email_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Alamat Dosen -->
                            <div class="form-group">
                                <label for="alamat_dosen">Alamat Dosen: <span class="text-danger">*</span></label>
                                <input type="text" name="alamat_dosen" id="alamat_dosen" placeholder="Alamat Dosen"
                                    class="form-control @error('alamat_dosen') is-invalid @enderror"
                                    value="{{ old('alamat_dosen', $dosen->alamat_dosen) }}">
                                @error('alamat_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Telepon Dosen -->
                            <div class="form-group">
                                <label for="no_telepon_dosen">Telepon Dosen:</label>
                                <input type="text" name="no_telepon_dosen" id="no_telepon_dosen" placeholder="Telepon Dosen"
                                    class="form-control @error('no_telepon_dosen') is-invalid @enderror"
                                    value="{{ old('no_telepon_dosen', $dosen->no_telepon_dosen) }}">
                                @error('no_telepon_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column (6 fields) -->
                        <div class="col-md-6">
                            <!-- Password Dosen -->
                            <div class="form-group">
                                <label for="password_dosen">Password Dosen: <span class="text-danger">*</span></label>
                                <input type="password" name="password_dosen" id="password_dosen"
                                    placeholder="Password Dosen"
                                    class="form-control @error('password_dosen') is-invalid @enderror"
                                    value="{{ old('password_dosen') }}">
                                @error('password_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir Dosen -->
                            <div class="form-group">
                                <label for="tanggal_lahir_dosen">Tanggal Lahir Dosen: <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir_dosen" id="tanggal_lahir_dosen"
                                    class="form-control @error('tanggal_lahir_dosen') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir_dosen', $dosen->tanggal_lahir_dosen) }}">
                                @error('tanggal_lahir_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Pendidikan Terakhir Dosen -->
                            <div class="form-group">
                                <label for="pendidikan_terakhir_dosen">Pendidikan Terakhir Dosen: <span class="text-danger">*</span></label>
                                <input type="text" name="pendidikan_terakhir_dosen" id="pendidikan_terakhir_dosen"
                                    placeholder="Pendidikan Terakhir Dosen"
                                    class="form-control @error('pendidikan_terakhir_dosen') is-invalid @enderror"
                                    value="{{ old('pendidikan_terakhir_dosen', $dosen->pendidikan_terakhir_dosen) }}">
                                @error('pendidikan_terakhir_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="form-group">
                                <label for="jenis_kelamin_dosen">Jenis Kelamin: <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin_dosen" id="jenis_kelamin_dosen"
                                    class="form-control @error('jenis_kelamin_dosen') is-invalid @enderror">
                                    <option value="L" {{ old('jenis_kelamin_dosen', $dosen->jenis_kelamin_dosen) == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin_dosen', $dosen->jenis_kelamin_dosen) == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('jenis_kelamin_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Status Kepegawaian -->
                            <div class="form-group">
                                <label for="status_kepegawaian_dosen">Status Kepegawaian: <span
                                        class="text-danger">*</span></label>
                                <select name="status_kepegawaian_dosen" id="status_kepegawaian_dosen"
                                    class="form-control @error('status_kepegawaian_dosen') is-invalid @enderror">
                                    <option value="PNS"
                                        {{ old('status_kepegawaian_dosen', $dosen->status_kepegawaian_dosen) == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="Honorer"
                                        {{ old('status_kepegawaian_dosen', $dosen->status_kepegawaian_dosen) == 'Honorer' ? 'selected' : '' }}>Honorer
                                    </option>
                                    <option value="Lainnya"
                                        {{ old('status_kepegawaian_dosen', $dosen->status_kepegawaian_dosen) == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                                @error('status_kepegawaian_dosen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Status Kepegawaian Lainnya (akan tampil jika status kepegawaian dosen adalah 'Lainnya') -->
                            <div class="form-group" id="status_kepegawaian_lainnya_group" style="display: none;">
                                <label for="status_kepegawaian_lainnya">Status Kepegawaian Lainnya:</label>
                                <input type="text" name="status_kepegawaian_lainnya" id="status_kepegawaian_lainnya"
                                    placeholder="Masukkan status kepegawaian lainnya"
                                    class="form-control @error('status_kepegawaian_lainnya') is-invalid @enderror"
                                    value="{{ old('status_kepegawaian_lainnya', $dosen->status_kepegawaian_lainnya) }}">
                                @error('status_kepegawaian_lainnya')
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
                            <a class="btn btn-secondary" href="{{ route('dosen.index') }}">Cancel</a>
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

        .fas.fa-eye {
            font-size: 20px;
            color: #007bff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Toggle visibility of 'status_kepegawaian_lainnya' field based on status_kepegawaian_dosen selection
        document.getElementById('status_kepegawaian_dosen').addEventListener('change', function() {
            var statusKepegawaian = this.value;
            var statusLainnyaGroup = document.getElementById('status_kepegawaian_lainnya_group');

            if (statusKepegawaian === 'Lainnya') {
                statusLainnyaGroup.style.display = 'block';
            } else {
                statusLainnyaGroup.style.display = 'none';
            }
        });
    </script>
@endpush
