@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Prodi Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('prodi.index') }}"
                            class="{{ request()->routeIs('prodi.index') ? 'active' : '' }}">Prodi Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('prodi.create') }}"
                            class="{{ request()->routeIs('prodi.create') ? 'active' : '' }}">Create Prodi</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Prodi Create Form -->
                <form method="POST" action="{{ route('prodi.store') }}">
                    @csrf

                    <!-- Nama Prodi -->
                    <div class="form-group">
                        <label for="nama_prodi">Nama Prodi:</label>
                        <input type="text" name="nama_prodi" id="nama_prodi" placeholder="Nama Prodi"
                            class="form-control @error('nama_prodi') is-invalid @enderror"
                            value="{{ old('nama_prodi') }}">
                        @error('nama_prodi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Kode Prodi -->
                    <div class="form-group">
                        <label for="kode_prodi">Kode Prodi:</label>
                        <input type="text" name="kode_prodi" id="kode_prodi" placeholder="Kode Prodi"
                            class="form-control @error('kode_prodi') is-invalid @enderror"
                            value="{{ old('kode_prodi') }}">
                        @error('kode_prodi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Jurusan -->
                    <div class="form-group">
                        <label for="jurusans_id">Jurusan:</label>
                        <select name="jurusans_id" id="jurusans_id" class="form-control @error('jurusans_id') is-invalid @enderror">
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}" {{ old('jurusans_id') == $jurusan->id ? 'selected' : '' }}>
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

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('prodi.index') }}">Cancel</a>
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
    </style>
@endpush
