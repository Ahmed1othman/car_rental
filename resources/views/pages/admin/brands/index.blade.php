@extends('layouts.admin_layout')

@section('title', 'List of ' . $modelName)

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">{{ $modelName }} List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">{{ $modelName }} List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm p-4 rounded-lg" role="alert">
                        <strong>Success:</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card card-outline card-shadow mb-4" style="border: 1px solid #dcdcdc; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-dark">{{ $modelName }} List</h3>
                            <a href="{{ route('admin.' . $modelName . '.create') }}" class="btn btn-primary shadow-sm">
                                <i class="fas fa-plus"></i> Add {{ $modelName }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-responsive-xl" style="background-color: #f9f9f9;">
                                <thead class="bg-dark text-light">
                                <tr>
                                    <th>#</th>
                                    <th>logo</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($items as $item)
                                    <tr class="text-center align-items-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($item->logo_path)
                                                <img src="{{ asset('storage/' . $item->logo_path) }}" alt="Brand Logo" class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
                                            @else
                                                <span>N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->translations->first()->name ?? 'N/A' }}</td>

                                        <td>
                                            <!-- Custom Toggle Switch -->
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-status" data-model="{{ $modelName }}" data-attribute="is_active" data-id="{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>{{ $item->created_at ? $item->created_at->format('d M, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{--                                                <a href="{{ route('admin.' . $modelName . '.show', $item->id) }}" class="btn btn-primary btn-sm shadow-sm mr-1">--}}
{{--                                                    <i class="fas fa-eye"></i> Show--}}
{{--                                                </a>--}}
                                                <a href="{{ route('admin.' . $modelName . '.edit', $item->id) }}" class="btn btn-info btn-sm shadow-sm mr-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm shadow-sm delete-btn" data-id="{{ $item->id }}" data-model="{{ $modelName }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-end">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
