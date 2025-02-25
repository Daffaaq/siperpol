@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Import Dosen</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dosen.index') }}" class="{{ request()->routeIs('dosen.index') ? 'active' : '' }}">
                            Dosen Management
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('dosen.show-import') }}"
                            class="{{ request()->routeIs('dosen.show-import') ? 'active' : '' }}">
                            Import Dosen
                        </a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Dosen Import Form -->
                <form method="POST" action="{{ route('dosen.import') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Jurusan Dropdown -->
                            <div class="form-group">
                                <label for="jurusan_id">Jurusan: <span class="text-danger">*</span></label>
                                <select name="jurusan_id" id="jurusan_id"
                                    class="form-control @error('jurusan_id') is-invalid @enderror">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($jurusans as $jurusan)
                                        <option value="{{ $jurusan->id }}"
                                            {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                            {{ $jurusan->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="form-group">
                                <label for="file">File Import Dosen: <span class="text-danger">*</span></label>
                                <input type="file" name="file" id="file"
                                    class="form-control @error('file') is-invalid @enderror">
                                @error('file')
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
                            <button type="submit" class="btn btn-primary">Import</button>
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
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'error',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000000,
                timerProgressBar: true,
            });
        @endif
        // Optional: You can add any JavaScript here if necessary for the import functionality
    </script>
@endpush
