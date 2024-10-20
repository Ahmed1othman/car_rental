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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
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
                                    <th>Default Image</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Options</th> <!-- New column header for all switches -->
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($item->default_image_path)
                                                <img src="{{ asset('storage/' . $item->default_image_path) }}" alt="post_image" class="img-" style="width: 200px; height: 200px; object-fit: cover;">
                                            @else
                                                <span>N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->translations->first()->name ?? 'N/A' }}</td>
                                        <td>{{ $item->brand->translations->first()->name ?? 'N/A' }}</td>
                                        <td>
                                            <!-- Combine all switches in one column -->
                                            <div class="switch-column">
                                                <div class="switch-wrapper">
                                                    <label for="only_on_afandina">Only on Afandina</label>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status" id="only_on_afandina" data-model="{{ $modelName }}" data-attribute="only_on_afandina" data-id="{{ $item->id }}" {{ $item->only_on_afandina ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>

                                                <div class="switch-wrapper">
                                                    <label for="is_flash_sale">Flash Sale</label>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status" id="is_flash_sale" data-model="{{ $modelName }}" data-attribute="is_flash_sale" data-id="{{ $item->id }}" {{ $item->is_flash_sale ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>

                                                <div class="switch-wrapper">
                                                    <label for="is_featured">Featured</label>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status" id="is_featured" data-model="{{ $modelName }}" data-attribute="is_featured" data-id="{{ $item->id }}" {{ $item->is_featured ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>

                                                <div class="switch-wrapper">
                                                    <label for="is_active">Active</label>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status" id="is_active" data-model="{{ $modelName }}" data-attribute="is_active" data-id="{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
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
                                                <button type="button" class="btn btn-danger btn-sm shadow-sm delete-btn mr-1" data-id="{{ $item->id }}" data-model="{{ $modelName }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>

                                                <a href="{{ route('admin.' . $modelName . '.edit_images', $item->id) }}" class="btn btn-primary btn-sm shadow-sm mr-1">
                                                    <i class="fas fa-images"></i> Images
                                                </a>
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
