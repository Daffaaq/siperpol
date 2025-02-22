@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Fasilitas Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('fasilitas.index') }}"
                            class="{{ request()->routeIs('fasilitas.index') ? 'active' : '' }}">Fasilitas Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('fasilitas.create') }}"
                            class="{{ request()->routeIs('fasilitas.create') ? 'active' : '' }}">Create Fasilitas</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Fasilitas Create Form -->
                <form method="POST" action="{{ route('fasilitas.store') }}">
                    @csrf

                    <!-- Nama Fasilitas (Multiple Input) -->
                    <div class="form-group">
                        <label for="nama_fasilitas">Nama Fasilitas:</label>
                        <div id="nama_fasilitas_fields">
                            <div class="input-group mb-2">
                                <input type="text" name="nama_fasilitas[]"
                                    class="form-control @error('nama_fasilitas.*') is-invalid @enderror"
                                    placeholder="Nama Fasilitas">
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" id="add_field"><i class="fas fa-plus"></i> Add
                            Fasilitas</button>
                        @error('nama_fasilitas.*')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('fasilitas.index') }}">Cancel</a>
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
        $(document).ready(function() {
            // Add new input field for fasilitas name
            $('#add_field').click(function() {
                var newField = '<div class="input-group mb-2">' +
                    '<input type="text" name="nama_fasilitas[]" class="form-control" placeholder="Nama Fasilitas">' +
                    '<div class="input-group-append">' +
                    '<button type="button" class="btn btn-danger remove-field"><i class="fas fa-minus"></i></button>' +
                    '</div>' +
                    '</div>';
                $('#nama_fasilitas_fields').append(newField);
            });

            // Remove the field when the remove button is clicked
            $(document).on('click', '.remove-field', function() {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
@endpush
