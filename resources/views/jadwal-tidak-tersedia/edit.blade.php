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
                    <li class="breadcrumb-item active" aria-current="page">Edit Jadwal Tidak Tersedia</li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Edit Jadwal Tidak Tersedia Form -->
                <form method="POST" action="{{ route('jadwal-tidak-tersedia.update', $jadwalTidakTersedia->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Jurusan -->
                    <div class="form-group">
                        <label for="jurusans_id">Jurusan:</label>
                        <select name="jurusans_id" id="jurusans_id"
                            class="form-control @error('jurusans_id') is-invalid @enderror">
                            <option value="">Pilih Jurusan</option>
                            @foreach ($getJurusan as $jurusan)
                                <option value="{{ $jurusan->id }}"
                                    {{ $jurusan->id == old('jurusans_id', $jadwalTidakTersedia->ruang->jurusan->id ?? '') ? 'selected' : '' }}>
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
                        <label for="ruangs_id">Ruang:</label>
                        <select name="ruangs_id" id="ruang"
                            class="form-control @error('ruangs_id') is-invalid @enderror">
                            <option value="">Pilih Ruang</option>
                            @if ($jadwalTidakTersedia->ruang)
                                <option value="{{ $jadwalTidakTersedia->ruang->id }}"
                                    {{ $jadwalTidakTersedia->ruang->id == old('ruangs_id', $jadwalTidakTersedia->ruangs_id) ? 'selected' : '' }}>
                                    {{ $jadwalTidakTersedia->ruang->nama_ruang }}
                                </option>
                            @else
                                <option value="">Ruang Tidak Tersedia</option>
                            @endif
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
                            value="{{ old('tanggal_mulai', $jadwalTidakTersedia->tanggal_mulai) }}">
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
                            value="{{ old('tanggal_selesai', $jadwalTidakTersedia->tanggal_selesai) }}">
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
                            class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $jadwalTidakTersedia->keterangan) }}</textarea>
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

        small.form-text {
            margin-top: -1.5rem;
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
            const jurusansSelect = document.getElementById('jurusans_id');
            const ruangSelect = document.getElementById('ruang');

            // Ambil jurusan_id yang terpilih dari server
            const jurusanId = '{{ old('jurusans_id', $jadwalTidakTersedia->ruang->jurusan->id ?? '') }}';

            // Jika jurusan_id ada, trigger change event atau panggil fungsi update ruang langsung
            if (jurusanId) {
                jurusansSelect.value = jurusanId;
                updateRuang(jurusanId); // Panggil fungsi untuk update ruang berdasarkan jurusan
            }

            // Fungsi untuk update ruang berdasarkan jurusan_id
            function updateRuang(jurusanId) {
                if (jurusanId) {
                    fetch(`/master-management/jadwal-tidak-tersedia/get-ruang/${jurusanId}`)
                        .then(response => response.json())
                        .then(data => {
                            ruangSelect.innerHTML =
                            '<option value="">Pilih Ruang</option>'; // Reset dropdown ruang

                            // Menambahkan data ruang ke dropdown
                            data.forEach(ruang => {
                                const option = document.createElement('option');
                                option.value = ruang.id;
                                option.textContent = ruang.nama_ruang;
                                ruangSelect.appendChild(option);
                            });

                            // Set ruang yang sudah dipilih sebelumnya jika ada
                            const ruangId = '{{ old('ruangs_id', $jadwalTidakTersedia->ruangs_id) }}';
                            if (ruangId) {
                                ruangSelect.value = ruangId;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching ruang:', error);
                        });
                } else {
                    ruangSelect.innerHTML = '<option value="">Pilih Ruang</option>';
                }
            }

            // Event listener untuk perubahan jurusan
            jurusansSelect.addEventListener('change', function() {
                const jurusanId = this.value;
                updateRuang(jurusanId); // Update ruang ketika jurusan diubah
            });
        });
    </script>
@endpush
