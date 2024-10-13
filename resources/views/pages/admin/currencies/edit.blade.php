@extends('layouts.admin_layout')

@section('title', 'Edit Currency')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">Edit Currency</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.currencies.index') }}">Currency List</a></li>
                            <li class="breadcrumb-item active">Edit Currency</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px; color: #f44336;"></i>
                            <div class="flex-grow-1">
                                <strong>Oops! We found some issues:</strong>
                                <ol class="mt-2 mb-0 pl-4" style="list-style: decimal;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ol>
                            </div>
                            <button type="button" class="close ml-3" data-dismiss="alert" aria-label="Close" style="font-size: 20px;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="card card-primary card-outline card-tabs shadow-lg">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.currencies.update', $currency->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
                                    <div class="form-group">
                                        <label for="code" class="font-weight-bold">Code</label>
                                        <input type="text" name="code" class="form-control form-control-lg shadow-sm" id="code" value="{{ old('code', $currency->code) }}" placeholder="e.g., USD, EUR">
                                    </div>

                                    <div class="form-group">
                                        <label for="symbol" class="font-weight-bold">Symbol</label>
                                        <input type="text" name="symbol" class="form-control form-control-lg shadow-sm" id="symbol" value="{{ old('code', $currency->symbol) }}" placeholder="e.g, $, €">
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Name</label>
                                        <input type="text" name="name" class="form-control form-control-lg shadow-sm" id="name" value="{{ old('name', $currency->name) }}" placeholder="Currency name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exchange_rate" class="font-weight-bold">Exchange Rate</label>
                                        <input type="number" step="0.0001" name="exchange_rate" class="form-control form-control-lg shadow-sm" id="exchange_rate" value="{{ old('exchange_rate', $currency->exchange_rate) }}" placeholder="Exchange rate">
                                    </div>
                                    <div class="form-group">
                                        <label for="is_default" class="font-weight-bold">Default Currency</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_default" class="custom-control-input" id="is_default" {{ old('is_default', $currency->is_default) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_default">Set as Default</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Active</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active', $currency->is_active) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg mt-3">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #28a745;
            border-color: #28a745;
        }

        .custom-switch .custom-control-input:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>
@endpush
