@extends('layouts.admin_layout')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Overview</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Main Stats -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-gradient-info">
                            <div class="inner">
                                <h3>{{ $carCount }}</h3>
                                <p>Total Cars</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <a href="{{ route('admin.cars.index') }}" class="small-box-footer">
                                View Cars <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-gradient-success">
                            <div class="inner">
                                <h3>{{ $brandCount }}</h3>
                                <p>Car Brands</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-copyright"></i>
                            </div>
                            <a href="{{ route('admin.brands.index') }}" class="small-box-footer">
                                View Brands <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-gradient-warning">
                            <div class="inner">
                                <h3>{{ $categoryCount }}</h3>
                                <p>Categories</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <a href="{{ route('admin.categories.index') }}" class="small-box-footer">
                                View Categories <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-gradient-danger">
                            <div class="inner">
                                <h3>{{ $carsByCategory->sum('cars_count') }}</h3>
                                <p>Total Listings</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <a href="{{ route('admin.cars.index') }}" class="small-box-footer">
                                View All <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>


<!-- Quick Actions -->
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-bolt mr-2"></i>
            Quick Actions
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Cars Management -->
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-car"></i></span>
                    <div class="info-box-content">
                        <h5 class="info-box-text">Cars</h5>
                        <div class="btn-group w-100">
                            <a href="{{ route('admin.cars.create') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-plus"></i> Add
                            </a>
                            <a href="{{ route('admin.cars.index') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-list"></i> List
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Brands Management -->
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-copyright"></i></span>
                    <div class="info-box-content">
                        <h5 class="info-box-text">Brands</h5>
                        <div class="btn-group w-100">
                            <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-plus"></i> Add
                            </a>
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-list"></i> List
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Management -->
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-gradient-warning">
                    <span class="info-box-icon"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <h5 class="info-box-text">Categories</h5>
                        <div class="btn-group w-100">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-plus"></i> Add
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-list"></i> List
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- Car Images -->
            <div class="col-md-4 col-sm-6">
                <div class="info-box bg-gradient-danger">
                    <span class="info-box-icon"><i class="fas fa-images"></i></span>
                    <div class="info-box-content">
                        <h5 class="info-box-text">Images</h5>
                        <div class="btn-group w-100">
                            <a href="{{ route('admin.cars.index') }}?show=images" class="btn btn-sm btn-light w-100">
                                <i class="fas fa-image"></i> Manage Images
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <!-- Car Details -->
            <div class="col-md-4 col-sm-6">
                <div class="info-box bg-gradient-primary">
                    <span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
                    <div class="info-box-content">
                        <h5 class="info-box-text">Details</h5>
                        <div class="btn-group w-100">
                            <a href="{{ route('admin.cars.index') }}?show=details" class="btn btn-sm btn-light w-100">
                                <i class="fas fa-list-alt"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Translations -->
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-gradient-secondary">
                    <span class="info-box-icon"><i class="fas fa-language"></i></span>
                    <div class="info-box-content">
                        <h5 class="info-box-text">Translations</h5>
                        <div class="btn-group w-100">
                            <a href="{{ route('admin.cars.index') }}?show=translations" class="btn btn-sm btn-light w-100">
                                <i class="fas fa-globe"></i> Manage Translations
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

                <!-- Content Sections -->
                <div class="row">
                    <!-- Latest Cars -->
                    <div class="col-md-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-car-side mr-2"></i>
                                    Latest Added Cars
                                </h3>
                                <div class="card-tools">
                                    <a href="{{ route('admin.cars.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Add New Car
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Car</th>
                                                <th>Brand</th>
                                                <th>Category</th>
                                                <th style="width: 100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($latestCars as $car)
                                                <tr>
                                                    <td>{{ $car->translations->first()->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ $car->brand->translations->first()->name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning">
                                                            {{ $car->category->translations->first()->name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('admin.cars.edit', $car->id) }}" 
                                                               class="btn btn-sm btn-primary"
                                                               title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="{{ route('admin.cars.edit_images', $car->id) }}" 
                                                               class="btn btn-sm btn-info"
                                                               title="Manage Images">
                                                                <i class="fas fa-images"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Stats -->
                    <div class="col-md-6">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-2"></i>
                                    Cars by Category
                                </h3>
                                <div class="card-tools">
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Add Category
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th style="width: 100px">Cars</th>
                                                <th style="width: 40%">Distribution</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($carsByCategory as $category)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.categories.edit', $category->id) }}">
                                                            {{ $category->translations->first()->name ?? 'N/A' }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">
                                                            {{ $category->cars_count }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-success" 
                                                                 style="width: {{ ($category->cars_count / $carCount) * 100 }}%"
                                                                 title="{{ round(($category->cars_count / $carCount) * 100, 1) }}%">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .small-box {
            border-radius: 0.5rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            transition: all 0.3s;
        }
        .small-box:hover {
            transform: translateY(-3px);
        }
        .small-box .icon {
            font-size: 70px;
            opacity: 0.3;
        }
        .small-box:hover .icon {
            opacity: 0.5;
        }
        .progress {
            height: 4px;
            margin: 8px 0;
        }
        .btn-group {
            gap: 5px;
        }
        .card {
            margin-bottom: 1rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        .card-outline {
            border-top: 3px solid;
        }
        .table td, .table th {
            padding: 0.75rem;
            vertical-align: middle;
        }
        .badge {
            padding: 0.5em 0.8em;
        }
        .info-box {
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            min-height: 120px;
            transition: all 0.3s ease;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .info-box-icon {
            width: 70px;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem 0 0 0.5rem;
        }
        .info-box-content {
            padding: 15px;
        }
        .info-box-content h5 {
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .btn-group {
            gap: 5px;
        }
        .btn-group .btn {
            flex: 1;
            text-align: center;
            border: none;
            padding: 8px;
            font-weight: 500;
            transition: all 0.2s;
            border-radius: 4px;
        }
        .btn-group .btn:hover {
            background: rgba(255,255,255,0.9);
            transform: translateY(-2px);
        }
        .btn-light {
            background: rgba(255,255,255,0.7);
            color: #333;
        }
    </style>
@endsection