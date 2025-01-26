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
                                        <label for="order" class="font-weight-bold">Display Order</label>
                                        <input type="number" name="order" class="form-control form-control-lg shadow-sm" id="order" value="{{ old('order', $item->order) }}" min="0">
                                        <small class="form-text text-muted">Lower numbers will be displayed first</small>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="is_active" class="font-weight-bold">Active</label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="{{$item->is_active}}" {{$item->is_active?'checked':''}}>
                                                    <label class="custom-control-label" for="is_active">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="show_in_home" class="font-weight-bold">Show In Home</label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="show_in_home" class="custom-control-input" id="show_in_home" value="{{$item->show_in_home}}" {{$item->show_in_home?'checked':''}}>
                                                    <label class="custom-control-label" for="show_in_home">Show In Home</label>
                                                </div>
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
                                                    <label for="question_{{ $lang->code }}" class="font-weight-bold">Question ({{ $lang->name }})</label>
                                                    <input type="text" name="question[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="question_{{ $lang->code }}" value="{{ old('question.' . $lang->code, $translation->question ?? '') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="answer_{{ $lang->code }}" class="font-weight-bold">Answer ({{ $lang->name }})</label>
                                                    <textarea name="answer[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="answer_{{ $lang->code }}" rows="4">{{ old('answer.' . $lang->code, $translation->answer ?? '') }}</textarea>
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
                                            @php
                                                $translation = $item->translations->where('locale', $lang->code)->first();
                                            @endphp
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-seo-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-seo-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="meta_title_{{ $lang->code }}" class="font-weight-bold">Meta Title ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_title[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_title_{{ $lang->code }}" value="{{ old('meta_title.' . $lang->code, $translation->meta_title ?? '') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">Meta Description ({{ $lang->name }})</label>
                                                    <textarea name="meta_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_description_{{ $lang->code }}" rows="3">{{ old('meta_description.' . $lang->code, $translation->meta_description ?? '') }}</textarea>
                                                </div>
                                                <!-- Meta Keywords Field -->
                                                <div class="form-group">
                                                    <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">Meta Keywords ({{ $lang->name }})</label>

                                                    @php
                                                        // Decode the JSON meta_keywords into an array
                                                        $metaKeywords = json_decode($translation->meta_keywords ?? '[]', true);

                                                        // Convert the array into a comma-separated string of keywords
                                                        $keywordString = implode(',', array_column($metaKeywords, 'value'));
                                                    @endphp

                                                    <input type="text" name="meta_keywords[{{ $lang->code }}]"
                                                           class="form-control form-control-lg shadow-sm"
                                                           id="meta_keywords_{{ $lang->code }}"
                                                           value="{{ old('meta_keywords.' . $lang->code, $keywordString) }}"
                                                           data-role="tagsinput" placeholder="Enter meta keywords">
                                                </div>

                                                <!-- Dynamic SEO Questions/Answers Section -->
                                                <div class="seo-questions-container" id="seo-questions-{{ $lang->code }}">
                                                    <label class="font-weight-bold">SEO Questions/Answers ({{ $lang->name }})</label>
                                                    @foreach($item->seoQuestions->where('locale', $lang->code) as $index => $seoQuestion)
                                                        <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                                            <div class="form-group">
                                                                <input type="text" name="seo_questions[{{ $lang->code }}][{{ $index }}][question]" class="form-control form-control-lg shadow-sm mb-2" value="{{ old('seo_questions.' . $lang->code . '.' . $index . '.question', $seoQuestion->question_text) }}" placeholder="Enter Question" />
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea name="seo_questions[{{ $lang->code }}][{{ $index }}][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer">{{ old('seo_questions.' . $lang->code . '.' . $index . '.answer', $seoQuestion->answer_text) }}</textarea>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                                        </div>
                                                    @endforeach
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
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function() {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
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

            $('[data-toggle="tooltip"]').tooltip();
        });


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
