@extends('layouts.admin_layout')

@section('title', 'Edit ' . $modelName)

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4" style="text-transform: capitalize;">Edit {{ $modelName }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.' . $modelName . '.index') }}" style="text-transform: capitalize;">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.' . $modelName . '.index') }}" style="text-transform: capitalize;">{{ $modelName }} List</a></li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">Edit {{ $modelName }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <!-- Display errors -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px;"></i>
                            <div class="flex-grow-1">
                                <strong>Oops! We found some issues:</strong>
                                <ol class="mt-2 mb-0 pl-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ol>
                            </div>
                            <button type="button" class="close ml-3" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="card card-primary card-outline card-tabs shadow-lg">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
                        <!-- Tabs Header -->
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-toggle="pill" href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated" aria-selected="false">
                                    <i class="fas fa-language"></i> Translated Data
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-toggle="pill" href="#custom-tabs-seo" role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                                    <i class="fas fa-search"></i> SEO Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Form -->
                        <form action="{{ route('admin.' . $modelName . '.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <!-- General Data Tab Content -->
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
                                    <div class="form-group">
                                        <label for="symbol" class="font-weight-bold">Symbol</label>
                                        <input type="text" name="symbol" class="form-control form-control-lg shadow-sm" id="general_field" value="{{ old('symbol', $item->symbol) }}">
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="code" class="font-weight-bold">Code</label>
                                            <input type="text" name="code" class="form-control form-control-lg shadow-sm" id="code" value="{{ old('code', $item->code) }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exchange_rate" class="font-weight-bold">Exchange Rate</label>
                                            <input type="number" name="exchange_rate" class="form-control form-control-lg shadow-sm" id="exchange_rate" value="{{ old('exchange_rate', $item->exchange_rate) }}">
                                        </div>


                                        <div class="form-group">
                                            <label for="is_default" class="font-weight-bold">Default</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="is_default" class="custom-control-input" id="is_default" value="{{$item->is_default}}" {{$item->is_default?'checked':''}}>
                                                <label class="custom-control-label" for="is_default">Default</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="is_active" class="font-weight-bold">Active</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="{{$item->is_active}}" {{$item->is_active?'checked':''}}>
                                                <label class="custom-control-label" for="is_active">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Translated Data Tab Content with Sub-tabs for Languages -->
                                <div class="tab-pane fade" id="custom-tabs-translated" role="tabpanel" aria-labelledby="custom-tabs-translated-tab">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        @foreach($activeLanguages as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link @if($loop->first) active @endif bg-light text-dark" id="pills-{{ $lang->code }}-tab" data-toggle="pill" href="#pills-{{ $lang->code }}" role="tab" aria-controls="pills-{{ $lang->code }}" aria-selected="true">{{ $lang->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-tabContent">
                                        @foreach($activeLanguages as $lang)
                                            @php
                                                $translation = $item->translations->where('locale', $lang->code)->first();
                                            @endphp
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="name_{{ $lang->code }}" class="font-weight-bold">Name ({{ $lang->name }})</label>
                                                    <input type="text" name="name[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="name_{{ $lang->code }}" value="{{ old('name.' . $lang->code, $translation->name ?? '') }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
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

@push('scripts')
    <script>


        $(document).ready(function() {
            @foreach($activeLanguages as $lang)
            var metaKeywordsInput = document.querySelector('#meta_keywords_{{ $lang->code }}');
            if (metaKeywordsInput) {
                new Tagify(metaKeywordsInput, {
                    placeholder: 'Enter meta keywords'
                });
            }
            @endforeach
        });
    </script>
@endpush
