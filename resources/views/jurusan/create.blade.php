@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Jurusan Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('jurusan.index') }}"
                            class="{{ request()->routeIs('jurusan.index') ? 'active' : '' }}">Jurusan Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('jurusan.create') }}"
                            class="{{ request()->routeIs('jurusan.create') ? 'active' : '' }}">Create Jurusan</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Jurusan Create Form -->
                <form method="POST" action="{{ route('jurusan.store') }}">
                    @csrf

                    <!-- Nama Jurusan -->
                    <div class="form-group">
                        <label for="nama_jurusan">Nama Jurusan:</label>
                        <input type="text" name="nama_jurusan" id="nama_jurusan" placeholder="Nama Jurusan"
                            class="form-control @error('nama_jurusan') is-invalid @enderror"
                            value="{{ old('nama_jurusan') }}">
                        @error('nama_jurusan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Kode Jurusan -->
                    <div class="form-group">
                        <label for="kode_jurusan">Kode Jurusan:</label>
                        <input type="text" name="kode_jurusan" id="kode_jurusan" placeholder="Kode Jurusan"
                            class="form-control @error('kode_jurusan') is-invalid @enderror"
                            value="{{ old('kode_jurusan') }}">
                        @error('kode_jurusan')
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
                            <a class="btn btn-secondary" href="{{ route('jurusan.index') }}">Cancel</a>
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
