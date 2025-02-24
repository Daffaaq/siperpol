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
                    <li class="breadcrumb-item">
                        <a href="{{ route('ruang.create') }}"
                            class="{{ request()->routeIs('ruang.create') ? 'active' : '' }}">Create Ruang</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Ruang Create Form -->
                <form method="POST" action="{{ route('ruang.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Nama Ruang -->
                    <div class="form-group">
                        <label for="nama_ruang">Nama Ruang:</label>
                        <input type="text" name="nama_ruang" id="nama_ruang" placeholder="Nama Ruang"
                            class="form-control @error('nama_ruang') is-invalid @enderror" value="{{ old('nama_ruang') }}">
                        @error('nama_ruang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Kode Ruang -->
                    <div class="form-group">
                        <label for="kode_ruang">Kode Ruang:</label>
                        <input type="text" name="kode_ruang" id="kode_ruang" placeholder="Kode Ruang"
                            class="form-control @error('kode_ruang') is-invalid @enderror" value="{{ old('kode_ruang') }}">
                        @error('kode_ruang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Kapasitas Ruang -->
                    <div class="form-group">
                        <label for="kapasitas_ruang">Kapasitas Ruang:</label>
                        <input type="number" name="kapasitas_ruang" id="kapasitas_ruang" placeholder="Kapasitas Ruang"
                            class="form-control @error('kapasitas_ruang') is-invalid @enderror"
                            value="{{ old('kapasitas_ruang') }}">
                        @error('kapasitas_ruang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tipe Ruang -->
                    <div class="form-group">
                        <label for="tipe_ruang">Tipe Ruang:</label>
                        <select name="tipe_ruang" id="tipe_ruang"
                            class="form-control @error('tipe_ruang') is-invalid @enderror">
                            <option value="">Pilih Tipe Ruang</option>
                            <option value="laboratorium" {{ old('tipe_ruang') == 'laboratorium' ? 'selected' : '' }}>
                                Laboratorium</option>
                            <option value="class" {{ old('tipe_ruang') == 'class' ? 'selected' : '' }}>Classroom</option>
                            <option value="auditorium" {{ old('tipe_ruang') == 'auditorium' ? 'selected' : '' }}>Auditorium
                            </option>
                            <option value="meeting" {{ old('tipe_ruang') == 'meeting' ? 'selected' : '' }}>Meeting Room
                            </option>
                        </select>
                        @error('tipe_ruang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Jurusan -->
                    <div class="form-group">
                        <label for="jurusans_id">Jurusan:</label>
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

                    <!-- Is Active -->
                    <div class="form-group">
                        <label for="is_active">Is Active:</label><br>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="is_active" id="active" value="1"
                                class="form-check-input @error('is_active') is-invalid @enderror"
                                {{ old('is_active') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="is_active" id="inactive" value="0"
                                class="form-check-input @error('is_active') is-invalid @enderror"
                                {{ old('is_active') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="inactive">Inactive</label>
                        </div>
                        @error('is_active')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Fasilitas -->
                    <div class="form-group">
                        <label for="fasilitas_id">Fasilitas:</label>
                        <select name="fasilitas_id[]" id="fasilitas_id" class="form-control choices" multiple>
                            @foreach ($fasilitas as $fasilitas_item)
                                <option value="{{ $fasilitas_item->id }}"
                                    {{ in_array($fasilitas_item->id, old('fasilitas_id', [])) ? 'selected' : '' }}>
                                    {{ $fasilitas_item->nama_fasilitas }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted mb-1">Pilih minimal satu fasilitas.</small>
                        @error('fasilitas_id')
                                {{ $message }}
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="form-group">
                        <label for="image">Image (optional):</label>
                        <input type="file" name="image" id="image"
                            class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('ruang.index') }}">Cancel</a>
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

@push('styles')
    <!-- Add this in the head section for Choices.js -->
    <link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <!-- Add this script before your closing body tag -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices.js on the select element
            new Choices('.choices', {
                removeItemButton: true,
                placeholderValue: 'Select Fasilitas'
            });
        });
    </script>
@endpush
