@extends('layouts.admin_layout')

@section('title', 'Add ' . $modelName)

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">Add {{$modelName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.' . $modelName . '.index') }}" style="text-transform: capitalize;">{{ $modelName }} List</a></li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">Add {{$modelName}}</li>
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
                        <!-- Tabs Header -->
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <!-- General Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                            <!-- Translated Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-toggle="pill" href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated" aria-selected="false">
                                    <i class="fas fa-language"></i> Translated Data
                                </a>
                            </li>
                            <!-- SEO Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-toggle="pill" href="#custom-tabs-seo" role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                                    <i class="fas fa-search"></i> SEO Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Form -->
                        <form action="{{ route('admin.'.$modelName.'.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <!-- General Data Tab Content -->
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
                                    <div class="form-group">
                                        <label for="per_page" class="font-weight-bold">Items Per Page</label>
                                        <input type="number" name="per_page" class="form-control form-control-lg shadow-sm" id="per_page" value="{{ old('per_page', 10) }}" min="1" max="50">
                                        <small class="form-text text-muted">Number of items to display per page (between 1 and 50)</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="general_field" class="font-weight-bold">General Field</label>
                                        <input type="text" name="general_field" class="form-control form-control-lg shadow-sm" id="general_field" value="{{ old('general_field') }}">
                                    </div>


                                    <div class="form-group text-center">
                                        <!-- Image Preview with Circular Border and Placeholder -->
                                        <div class="mb-3">
                                            <img id="imagePreviewLogo" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='200' viewBox='0 0 150 150'%3E%3Crect width='100%25' height='100%25' fill='%23ddd'/%3E%3Ctext x='50%25' y='50%25' fill='%23555' font-size='20' text-anchor='middle' dy='.3em'%3E600x200%3C/text%3E%3C/svg%3E" alt="Logo Preview" class="rounded-circle shadow image-preview" style="max-height: 150px; width: 150px; object-fit: cover; border: 2px solid #ddd;">
                                        </div>

                                        <!-- File Input for Logo Upload -->
                                        <div class="custom-file">
                                            <input type="file" name="logo_path" class="custom-file-input image-upload @error('logo_path') is-invalid @enderror" id="logo_path" data-preview="imagePreviewLogo">
                                            <label class="custom-file-label" for="logo_path">Upload Logo</label>
                                        </div>

                                        <!-- Error Handling -->
                                        @error('logo_path')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Active</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active')}}>
                                            <label class="custom-control-label" for="is_active">Active</label>
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
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="name_{{ $lang->code }}" class="font-weight-bold">Name ({{ $lang->name }})</label>
                                                    <input type="text" name="name[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="name_{{ $lang->code }}" value="{{ old('name.'.$lang->code) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="description_{{ $lang->code }}" class="font-weight-bold">Description ({{ $lang->name }})</label>
                                                    <textarea name="description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="description_{{ $lang->code }}" rows="4">{{ old('description.'.$lang->code) }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- SEO Data Tab Content -->
                                <div class="tab-pane fade" id="custom-tabs-seo" role="tabpanel" aria-labelledby="custom-tabs-seo-tab">
                                    <ul class="nav nav-pills mb-3" id="pills-seo-tab" role="tablist">
                                        @foreach($activeLanguages as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link @if($loop->first) active @endif bg-light text-dark" id="pills-seo-{{ $lang->code }}-tab" data-toggle="pill" href="#pills-seo-{{ $lang->code }}" role="tab" aria-controls="pills-seo-{{ $lang->code }}" aria-selected="true">{{ $lang->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-seo-tabContent">
                                        @foreach($activeLanguages as $lang)
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-seo-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-seo-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="meta_title_{{ $lang->code }}" class="font-weight-bold">Meta Title ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_title[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_title_{{ $lang->code }}" value="{{ old('meta_title.'.$lang->code) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">Meta Description ({{ $lang->name }})</label>
                                                    <textarea name="meta_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_description_{{ $lang->code }}" rows="3">{{ old('meta_description.'.$lang->code) }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">Meta Keywords ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_keywords[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_keywords_{{ $lang->code }}" data-role="tagsinput" value="{{ old('meta_keywords.'.$lang->code) }}">
                                                </div>

                                                <div class="row card">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="robots_index_{{ $lang->code }}" class="font-weight-bold">
                                                                Robot Index ({{ $lang->name }})
                                                            </label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox"
                                                                       name="robots_index[{{ $lang->code }}]"
                                                                       class="custom-control-input"
                                                                       id="robots_index_{{ $lang->code }}"
                                                                       value="index"
                                                                    {{ old('robots_index.'.$lang->code, $currentValues['robots_index'][$lang->code] ?? '') === 'index' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="robots_index_{{ $lang->code }}">Index</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="robots_follow_{{ $lang->code }}" class="font-weight-bold">
                                                                Robot Follow ({{ $lang->name }})
                                                            </label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox"
                                                                       name="robots_follow[{{ $lang->code }}]"
                                                                       class="custom-control-input"
                                                                       id="robots_follow_{{ $lang->code }}"
                                                                       value="follow"
                                                                    {{ old('robots_follow.'.$lang->code, $currentValues['robots_follow'][$lang->code] ?? '') === 'follow' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="robots_follow_{{ $lang->code }}">Follow</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Dynamic SEO Questions/Answers Section -->
                                                <div class="seo-questions-container" id="seo-questions-{{ $lang->code }}">
                                                    <label class="font-weight-bold">SEO Questions/Answers ({{ $lang->name }})</label>
                                                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                                        <div class="form-group">
                                                            <input type="text" name="seo_questions[{{ $lang->code }}][0][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea name="seo_questions[{{ $lang->code }}][0][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-info add-question mt-3" data-lang="{{ $lang->code }}">
                                                    <i class="fas fa-plus"></i> Add Question
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-success btn-lg mt-3">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


@push('scripts')

    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function() {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
                console.log('Adding question for language:', lang, 'Count:', count); // Debugging line
                var newQuestionGroup = `
                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                        <div class="form-group">
                            <input type="text" name="seo_questions[` + lang + `][` + count + `][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                        </div>
                        <div class="form-group">
                            <textarea name="seo_questions[` + lang + `][` + count + `][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                    </div>`;
                container.append(newQuestionGroup);
            });

            // Function to remove an SEO Question/Answer
            $(document).on('click', '.remove-question', function() {
                $(this).closest('.seo-question-group').remove();
            });


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
