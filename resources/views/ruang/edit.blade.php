@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Edit Ruang</h6>
                <a href="{{ route('ruang.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <form action="{{ route('ruang.update', $ruang->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama_ruang">Nama Ruang</label>
                        <input type="text" name="nama_ruang"
                            class="form-control @error('nama_ruang') is-invalid @enderror"
                            value="{{ old('nama_ruang', $ruang->nama_ruang) }}" required>
                        @error('nama_ruang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode_ruang">Kode Ruang</label>
                        <input type="text" name="kode_ruang"
                            class="form-control @error('kode_ruang') is-invalid @enderror"
                            value="{{ old('kode_ruang', $ruang->kode_ruang) }}" required>
                        @error('kode_ruang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="is_active">Status</label>
                        <select name="is_active" class="form-control @error('is_active') is-invalid @enderror" required>
                            <option value="1" {{ old('is_active', $ruang->is_active) == 1 ? 'selected' : '' }}>Active
                            </option>
                            <option value="0" {{ old('is_active', $ruang->is_active) == 0 ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kapasitas_ruang">Kapasitas Ruang</label>
                        <input type="number" name="kapasitas_ruang"
                            class="form-control @error('kapasitas_ruang') is-invalid @enderror"
                            value="{{ old('kapasitas_ruang', $ruang->kapasitas_ruang) }}" required>
                        @error('kapasitas_ruang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipe_ruang">Tipe Ruang</label>
                        <select name="tipe_ruang" class="form-control @error('tipe_ruang') is-invalid @enderror" required>
                            <option value="laboratorium"
                                {{ old('tipe_ruang', $ruang->tipe_ruang) == 'laboratorium' ? 'selected' : '' }}>
                                Laboratorium</option>
                            <option value="class"
                                {{ old('tipe_ruang', $ruang->tipe_ruang) == 'class' ? 'selected' : '' }}>Class</option>
                            <option value="auditorium"
                                {{ old('tipe_ruang', $ruang->tipe_ruang) == 'auditorium' ? 'selected' : '' }}>Auditorium
                            </option>
                            <option value="meeting"
                                {{ old('tipe_ruang', $ruang->tipe_ruang) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                        </select>
                        @error('tipe_ruang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jurusans_id">Jurusan</label>
                        <select name="jurusans_id" class="form-control @error('jurusans_id') is-invalid @enderror" required>
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}"
                                    {{ old('jurusans_id', $ruang->jurusans_id) == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('jurusans_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        @if ($ruang->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $ruang->image) }}" alt="Current Image"
                                    class="img-thumbnail" style="width: 200px;">
                            </div>
                        @endif
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fasilitas_id">Fasilitas</label>
                        <select name="fasilitas_id[]"
                            class="form-control choices @error('fasilitas_id') is-invalid @enderror" multiple>
                            @foreach ($fasilitas as $fasilitas_item)
                                <option value="{{ $fasilitas_item->id }}"
                                    @if (in_array($fasilitas_item->id, old('fasilitas_id', $ruang->fasilitas->pluck('id')->toArray()))) selected @endif>
                                    {{ $fasilitas_item->nama_fasilitas }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih minimal satu fasilitas.</small>
                        @error('fasilitas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Ruang</button>
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

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'error',
                    text: '{{ session('error') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            @endif
        });
    </script>
@endpush
