@extends('layouts.admin_layout')

@section('title', 'List of Currencies')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">currencies List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">currencies List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '{{ session('success') }}',
                            timer: 3000,
                            showConfirmButton: false,
                        });
                    </script>
                @endif

                <div class="card card-outline card-shadow mb-4" style="border: 1px solid #dcdcdc; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-dark">currencies List</h3>
                            <a href="{{ route('admin.currencies.create') }}" class="btn btn-primary shadow-sm">
                                <i class="fas fa-plus"></i> Add currencies
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-responsive-xl" style="background-color: #f9f9f9;">
                                <thead class="bg-dark text-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Symbol</th>
                                    <th>Default</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name ?? 'N/A' }}</td>
                                        <td>{{ $item->code ?? 'N/A' }}</td>
                                        <td>{{ $item->symbol ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if($item->is_default)
                                                <span class="badge badge-success" title="Default Currency">
                                                    <i class="fas fa-check"></i> Default
                                                </span>
                                            @else
                                                <span class="badge badge-secondary" title="Not Default Currency">
                                                    <i class="fas fa-times"></i> Not Default
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Custom Toggle Switch -->
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-status" data-model="currencies" data-id="{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>{{ $item->created_at ? $item->created_at->format('d M, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.currencies.show', $item->id) }}" class="btn btn-primary btn-sm shadow-sm mr-1">
                                                    <i class="fas fa-eye"></i> Show
                                                </a>
                                                <a href="{{ route('admin.currencies.edit', $item->id) }}" class="btn btn-info btn-sm shadow-sm mr-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm shadow-sm delete-btn" data-id="{{ $item->id }}" data-model="currencies">
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
                        <div class="d-flex justify-content-center">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
