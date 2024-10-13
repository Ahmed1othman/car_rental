@extends('layouts.admin_layout')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
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

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Statistic Cards -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- Total Cars -->
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-car"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Cars</span>
                                <span class="info-box-number">{{ $carCount }}</span>
                                <span class="progress-description">
                                    <a href="{{ route('admin.cars.index') }}" class="text-white">View All Cars <i class="fas fa-arrow-circle-right"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <!-- Total Categories -->
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Categories</span>
                                <span class="info-box-number">{{ $categoryCount }}</span>
                                <span class="progress-description">
                                    <a href="{{ route('admin.categories.index') }}" class="text-white">View All Categories <i class="fas fa-arrow-circle-right"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <!-- Total Brands -->
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-copyright"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Brands</span>
                                <span class="info-box-number">{{ $brandCount }}</span>
                                <span class="progress-description">
                                    <a href="{{ route('admin.brands.index') }}" class="text-white">View All Brands <i class="fas fa-arrow-circle-right"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <!-- Total Users -->
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Users</span>
                                <span class="info-box-number">{{ $userCount }}</span>
                                <span class="progress-description">
                                    Manage Users <i class="fas fa-arrow-circle-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title text-white">Monthly Revenue</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="revenueChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links Section -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header bg-dark">
                                <h3 class="card-title text-white">Quick Links</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <a href="{{ route('admin.cars.create') }}" class="quick-link">
                                            <div class="quick-link-icon bg-info">
                                                <i class="fas fa-car"></i>
                                            </div>
                                            <div class="quick-link-text">
                                                Add Car
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <a href="{{ route('admin.categories.create') }}" class="quick-link">
                                            <div class="quick-link-icon bg-success">
                                                <i class="fas fa-list"></i>
                                            </div>
                                            <div class="quick-link-text">
                                                Add Category
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <a href="{{ route('admin.brands.create') }}" class="quick-link">
                                            <div class="quick-link-icon bg-warning">
                                                <i class="fas fa-copyright"></i>
                                            </div>
                                            <div class="quick-link-text">
                                                Add Brand
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <a href="{{ route('admin.faqs.index') }}" class="quick-link">
                                            <div class="quick-link-icon bg-danger">
                                                <i class="fas fa-question-circle"></i>
                                            </div>
                                            <div class="quick-link-text">
                                                Manage FAQs
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .info-box .info-box-icon {
            height: 80px;
            line-height: 80px;
            font-size: 32px;
        }
        .info-box-content .info-box-text, .info-box-content .info-box-number {
            font-size: 18px;
        }
        .quick-link {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-decoration: none;
            color: inherit;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: background 0.3s;
        }
        .quick-link:hover {
            background: #f0f0f0;
        }
        .quick-link-icon {
            font-size: 28px;
            padding: 10px;
            border-radius: 50%;
            color: #fff;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quick-link-text {
            font-size: 16px;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($revenueData) !!},
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
@endpush
