@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Tipe Dokumen Peminjaman Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('tipe-dokumen-peminjaman.index') }}"
                            class="{{ request()->routeIs('tipe-dokumen-peminjaman.index') ? 'active' : '' }}">
                            Tipe Dokumen Peminjaman Management
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('tipe-dokumen-peminjaman.edit', $tipe_dokumen_peminjaman->id) }}"
                            class="{{ request()->routeIs('tipe-dokumen-peminjaman.edit') ? 'active' : '' }}">
                            Edit Tipe Dokumen Peminjaman
                        </a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Tipe Dokumen Peminjaman Edit Form -->
                <form method="POST" action="{{ route('tipe-dokumen-peminjaman.update', $tipe_dokumen_peminjaman->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Tipe Dokumen -->
                    <div class="form-group">
                        <label for="tipe_dokumen">Tipe Dokumen:</label>
                        <input type="text" name="tipe_dokumen" id="tipe_dokumen" placeholder="Tipe Dokumen"
                            class="form-control @error('tipe_dokumen') is-invalid @enderror"
                            value="{{ old('tipe_dokumen', $tipe_dokumen_peminjaman->tipe_dokumen) }}">
                        @error('tipe_dokumen')
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
                                {{ old('is_active', $tipe_dokumen_peminjaman->is_active) == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="is_active" id="inactive" value="0"
                                class="form-check-input @error('is_active') is-invalid @enderror"
                                {{ old('is_active', $tipe_dokumen_peminjaman->is_active) == '0' ? 'checked' : '' }}>
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
                            <a class="btn btn-secondary" href="{{ route('tipe-dokumen-peminjaman.index') }}">Cancel</a>
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
