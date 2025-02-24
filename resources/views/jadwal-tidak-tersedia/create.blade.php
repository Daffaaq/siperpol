@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Jadwal Tidak Tersedia Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('jadwal-tidak-tersedia.index') }}"
                            class="{{ request()->routeIs('jadwal-tidak-tersedia.index') ? 'active' : '' }}">Jadwal Tidak
                            Tersedia Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('jadwal-tidak-tersedia.create') }}"
                            class="{{ request()->routeIs('jadwal-tidak-tersedia.create') ? 'active' : '' }}">Create Jadwal
                            Tidak Tersedia</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Jadwal Tidak Tersedia Create Form -->
                <form method="POST" action="{{ route('jadwal-tidak-tersedia.store') }}">
                    @csrf

                    <!-- Jurusan -->
                    <div class="form-group">
                        <label for="jurusans_id">Jurusan:</label>
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
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Ruang -->
                    <div class="form-group">
                        <label for="ruang">Ruang:</label>
                        <select name="ruangs_id" id="ruang" class="form-control @error('ruang') is-invalid @enderror">
                            <option value="">Pilih Ruang</option>
                        </select>
                        @error('ruangs_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai:</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            value="{{ old('tanggal_mulai') }}">
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="form-group">
                        <label for="tanggal_selesai">Tanggal Selesai:</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                            value="{{ old('tanggal_selesai') }}">
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <textarea name="keterangan" id="keterangan" rows="4"
                            class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('jadwal-tidak-tersedia.index') }}">Cancel</a>
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

        small.form-text {
            margin-top: -1.5rem;
            /* Mengurangi jarak atas */
        }
    </style>
@endpush
@push('scripts')
    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'error',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 100000,
                timerProgressBar: true,
            });
        @endif
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch ruang data based on jurusan selection
            const jurusansSelect = document.getElementById('jurusans_id');
            const ruangSelect = document.getElementById('ruang');
            console.log(jurusansSelect);
            jurusansSelect.addEventListener('change', function() {
                const jurusanId = this.value;
                if (jurusanId) {
                    fetch(`/master-management/jadwal-tidak-tersedia/get-ruang/${jurusanId}`)
                        .then(response => response.json())
                        .then(data => {
                            ruangSelect.innerHTML = '<option value="">Pilih Ruang</option>';
                            data.forEach(ruang => {
                                const option = document.createElement('option');
                                option.value = ruang.id;
                                option.textContent = ruang.nama_ruang;
                                ruangSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                        });
                } else {
                    ruangSelect.innerHTML = '<option value="">Pilih Ruang</option>';
                }
            });
        });
    </script>
@endpush
