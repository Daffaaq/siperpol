@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-lg mb-5">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Ruang Details</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('ruang.index') }}"
                            class="{{ request()->routeIs('ruang.index') ? 'active' : '' }}">Ruang Management</a>
                    </li>
                    <li class="breadcrumb-item active">Detail Ruang</li>
                </ol>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('ruang.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <!-- Ruang Information -->
                <div class="row justify-content-center">
                    <div class="col-lg-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white border-bottom">
                                <h5 class="m-0 font-weight-bold">General Information</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Nama Ruang:</strong> {{ $ruang->nama_ruang }}</p>
                                <p><strong>Kode Ruang:</strong> {{ $ruang->kode_ruang }}</p>
                                <p><strong>Kapasitas:</strong> {{ $ruang->kapasitas_ruang }} Orang</p>
                                <p><strong>Tipe Ruang:</strong> {{ $ruang->tipe_ruang }}</p>
                                <p><strong>Status:</strong>
                                    <span class="badge {{ $ruang->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $ruang->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ruang Facilities -->
                <div class="row justify-content-center">
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white border-bottom">
                                <h5 class="m-0 font-weight-bold">Facilities</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($ruang->fasilitas as $index => $fasilitas)
                                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-duration="1000"
                                            data-aos-delay="{{ $index * 200 }}">
                                            <!-- Apply AOS animation with a delay for each card -->
                                            <div class="card shadow-sm border-0">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="fas fa-cogs fa-lg text-primary"></i>
                                                        <!-- Icon for each facility -->
                                                    </div>
                                                    <h6 class="font-weight-bold text-dark">{{ $fasilitas->nama_fasilitas }}
                                                    </h6>
                                                    <span class="badge badge-success p-1">Available</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Image Section -->
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white border-bottom">
                                <h5 class="m-0 font-weight-bold">Ruang Image</h5>
                            </div>
                            <div class="card-body text-center">
                                @if ($ruang->image)
                                    <img src="{{ asset('storage/' . $ruang->image) }}" class="img-fluid" alt="Ruang Image"
                                        style="max-height: 350px; object-fit: cover;">
                                @else
                                    <span>No Image Available</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('styles')
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
@endpush
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

        .card-header {
            background-color: #ffffff;
            border-bottom: 2px solid #ddd;
        }

        .card-body {
            background-color: #f8f9fc;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .btn-outline-primary {
            font-size: 0.875rem;
            border-radius: 30px;
            padding: 8px 15px;
        }

        .badge {
            font-size: 0.75rem;
            border-radius: 12px;
            padding: 5px 10px;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .col-md-4 {
            flex: 0 0 32%;
            max-width: 32%;
        }

        .card-body h6 {
            font-size: 1rem;
            font-weight: 500;
        }

        .card-body .badge {
            font-size: 0.9rem;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000, // Animation duration in milliseconds
            offset: 25, // How early the animation triggers (before scrolling)
            delay: 10, // Global delay
        });
    </script>
@endpush
