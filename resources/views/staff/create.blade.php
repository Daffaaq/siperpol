@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Staff Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('staff.index') }}"
                            class="{{ request()->routeIs('staff.index') ? 'active' : '' }}">Staff Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('staff.create') }}"
                            class="{{ request()->routeIs('staff.create') ? 'active' : '' }}">Create Staff</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Staff Create Form -->
                <form method="POST" action="{{ route('staff.store') }}">
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
                                    @foreach ($jurusans as $jurusan)
                                        <option value="{{ $jurusan->id }}"
                                            {{ old('jurusans_id') == $jurusan->id ? 'selected' : '' }}>
                                            {{ $jurusan->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusans_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- Nama Staff -->
                            <div class="form-group">
                                <label for="nama_staff">Nama Staff: <span class="text-danger">*</span></label>
                                <input type="text" name="nama_staff" id="nama_staff" placeholder="Nama Staff"
                                    class="form-control @error('nama_staff') is-invalid @enderror"
                                    value="{{ old('nama_staff') }}">
                                @error('nama_staff')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email Staff -->
                            <div class="form-group">
                                <label for="email_staff">Email Staff: <span class="text-danger">*</span></label>
                                <input type="email" name="email_staff" id="email_staff" placeholder="Email Staff"
                                    class="form-control @error('email_staff') is-invalid @enderror"
                                    value="{{ old('email_staff') }}">
                                @error('email_staff')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Alamat Staff -->
                            <div class="form-group">
                                <label for="alamat_staff">Alamat Staff:</label>
                                <input type="text" name="alamat_staff" id="alamat_staff" placeholder="Alamat Staff"
                                    class="form-control @error('alamat_staff') is-invalid @enderror"
                                    value="{{ old('alamat_staff') }}">
                                @error('alamat_staff')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- No Telepon Staff -->
                            <div class="form-group">
                                <label for="no_telepon_staff">No Telepon Staff:</label>
                                <input type="text" name="no_telepon_staff" id="no_telepon_staff"
                                    placeholder="No Telepon Staff"
                                    class="form-control @error('no_telepon_staff') is-invalid @enderror"
                                    value="{{ old('no_telepon_staff') }}">
                                @error('no_telepon_staff')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Pendidikan Terakhir Staff -->
                            <div class="form-group">
                                <label for="pendidikan_terakhir_staff">Pendidikan Terakhir Staff:</label>
                                <input type="text" name="pendidikan_terakhir_staff" id="pendidikan_terakhir_staff"
                                    placeholder="Pendidikan Terakhir Staff"
                                    class="form-control @error('pendidikan_terakhir_staff') is-invalid @enderror"
                                    value="{{ old('pendidikan_terakhir_staff') }}">
                                @error('pendidikan_terakhir_staff')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column (5 fields) -->
                        <div class="col-md-6">
                            <! -- Nama Panggilan Staff -->
                                <div class="form-group">
                                    <label for="nama_panggilan_staff">Nama Panggilan Staff: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nama_panggilan_staff" id="nama_panggilan_staff"
                                        placeholder="Nama Staff"
                                        class="form-control @error('nama_panggilan_staff') is-invalid @enderror"
                                        value="{{ old('nama_panggilan_staff') }}">
                                    @error('nama_panggilan_staff')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <!-- Password Staff -->
                                <div class="form-group">
                                    <label for="password_staff">Password Staff: <span class="text-danger">*</span></label>
                                    <input type="password" name="password_staff" id="password_staff"
                                        placeholder="Password Staff"
                                        class="form-control @error('password_staff') is-invalid @enderror"
                                        value="{{ old('password_staff') }}">
                                    @error('password_staff')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Tanggal Lahir Staff -->
                                <div class="form-group">
                                    <label for="tanggal_lahir_staff">Tanggal Lahir Staff: <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_lahir_staff" id="tanggal_lahir_staff"
                                        class="form-control @error('tanggal_lahir_staff') is-invalid @enderror"
                                        value="{{ old('tanggal_lahir_staff') }}">
                                    @error('tanggal_lahir_staff')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Jenis Kelamin Staff -->
                                <div class="form-group">
                                    <label for="jenis_kelamin_staff">Jenis Kelamin: <span
                                            class="text-danger">*</span></label>
                                    <select name="jenis_kelamin_staff" id="jenis_kelamin_staff"
                                        class="form-control @error('jenis_kelamin_staff') is-invalid @enderror">
                                        <option value="L" {{ old('jenis_kelamin_staff') == 'L' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin_staff') == 'P' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin_staff')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Status Kepegawaian Staff -->
                                <div class="form-group">
                                    <label for="status_kepegawaian_staff">Status Kepegawaian:</label>
                                    <select name="status_kepegawaian_staff" id="status_kepegawaian_staff"
                                        class="form-control @error('status_kepegawaian_staff') is-invalid @enderror">
                                        <option value="PNS"
                                            {{ old('status_kepegawaian_staff') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="Honorer"
                                            {{ old('status_kepegawaian_staff') == 'Honorer' ? 'selected' : '' }}>Honorer
                                        </option>
                                        <option value="Lainnya"
                                            {{ old('status_kepegawaian_staff') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                        </option>
                                    </select>
                                    @error('status_kepegawaian_staff')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Status Kepegawaian Lainnya (optional field) -->
                                <div class="form-group" id="status_kepegawaian_lainnya_group" style="display: none;">
                                    <label for="status_kepegawaian_lainnya">Status Kepegawaian Lainnya:</label>
                                    <input type="text" name="status_kepegawaian_lainnya"
                                        id="status_kepegawaian_lainnya" placeholder="Masukkan status kepegawaian lainnya"
                                        class="form-control @error('status_kepegawaian_lainnya') is-invalid @enderror"
                                        value="{{ old('status_kepegawaian_lainnya') }}">
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
                            <a class="btn btn-secondary" href="{{ route('staff.index') }}">Cancel</a>
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
    </style>
@endpush

@push('scripts')
    <script>
        // Toggle visibility of 'status_kepegawaian_lainnya' field based on status_kepegawaian_staff selection
        document.getElementById('status_kepegawaian_staff').addEventListener('change', function() {
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
