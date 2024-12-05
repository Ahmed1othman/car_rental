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

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm p-4 rounded-lg" role="alert">
                        <strong>Success:</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

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

                                    <input type="hidden" name="page_name" class="form-control shadow-sm" id="page_name" value="{{ old('page_name',$item->page_name) }}">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group text-center">
                                                <!-- Image Preview with Circular Border and Placeholder -->
                                                <div class="mb-3">
                                                    <img id="imagePreview_why_choose_image_path" src="{{$item->why_choose_image_path?asset('storage/'.$item->why_choose_image_path):'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'400\' viewBox=\'0 0 300 400\'%3E%3Crect width=\'100%25\' height=\'100%25\' fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23555\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\'%3E400x300%3C/text%3E%3C/svg%3E'}}" alt="Logo Preview" class="shadow image-rectangle-preview" style="height: 300px; width: 300px; object-fit: cover; border: 2px solid #ddd;">
                                                </div>

                                                <!-- File Input for Logo Upload -->
                                                <div class="custom-file">
                                                    <input type="file" name="why_choose_image_path" class="custom-file-input image-upload @error('why_choose_image_path') is-invalid @enderror" id="image_path" data-preview="imagePreview_why_choose_image_path">
                                                    <label class="custom-file-label" for="why_choose_image_path">Upload Why Choose Image</label>
                                                </div>

                                                <!-- Error Handling -->
                                                @error('why_choose_image_path')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group text-center">--}}
{{--                                                <!-- Image Preview with Circular Border and Placeholder -->--}}
{{--                                                <div class="mb-3">--}}
{{--                                                    <img id="imagePreview_our_vision_image_path" src="{{$item->our_vision_image_path?asset('storage/'.$item->our_vision_image_path):'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'400\' viewBox=\'0 0 300 400\'%3E%3Crect width=\'100%25\' height=\'100%25\' fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23555\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\'%3E400x300%3C/text%3E%3C/svg%3E'}}" alt="Logo Preview" class="shadow image-rectangle-preview" style="height: 300px; width: 300px; object-fit: cover; border: 2px solid #ddd;">--}}
{{--                                                </div>--}}

{{--                                                <!-- File Input for Logo Upload -->--}}
{{--                                                <div class="custom-file">--}}
{{--                                                    <input type="file" name="our_vision_image_path" class="custom-file-input image-upload @error('our_vision_image_path') is-invalid @enderror" id="our_vision_image_path" data-preview="imagePreview_our_vision_image_path">--}}
{{--                                                    <label class="custom-file-label" for="our_vision_image_path">Upload Our Vision Image</label>--}}
{{--                                                </div>--}}

{{--                                                <!-- Error Handling -->--}}
{{--                                                @error('our_vision_image_path')--}}
{{--                                                <span class="invalid-feedback">{{ $message }}</span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="col-md-6">
                                            <div class="form-group text-center">
                                                <!-- Image Preview with Circular Border and Placeholder -->
                                                <div class="mb-3">
                                                    <img id="imagePreview_our_mission_image_path" src="{{$item->our_mission_image_path?asset('storage/'.$item->our_mission_image_path):'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'400\' viewBox=\'0 0 300 400\'%3E%3Crect width=\'100%25\' height=\'100%25\' fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23555\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\'%3E400x300%3C/text%3E%3C/svg%3E'}}" alt="Logo Preview" class="shadow image-rectangle-preview" style="height: 300px; width: 300px; object-fit: cover; border: 2px solid #ddd;">
                                                </div>

                                                <!-- File Input for Logo Upload -->
                                                <div class="custom-file">
                                                    <input type="file" name="our_mission_image_path" class="custom-file-input image-upload @error('our_mission_image_path') is-invalid @enderror" id="our_mission_image_path" data-preview="imagePreview_our_mission_image_path">
                                                    <label class="custom-file-label" for="our_mission_image_path">Upload Our Mission Image</label>
                                                </div>

                                                <!-- Error Handling -->
                                                @error('our_mission_image_path')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
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
                                                    <label for="about_main_header_title_[{{ $lang->code }}]" class="font-weight-bold">About Us Main Header Title ([{{ $lang->name }}])</label>
                                                    <input type="text" name="about_main_header_title[{{ $lang->code }}]" class="form-control shadow-sm" id="about_main_header_title_[{{ $lang->code }}]" value="{{ old('about_main_header_title.' . $lang->code ,$translation->about_main_header_title??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="about_main_header_paragraph_{{ $lang->code }}" class="font-weight-bold">About Us Main Header Paragraph ({{ $lang->name }})</label>
                                                    <textarea name="about_main_header_paragraph[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm " id="about_main_header_paragraph_{{ $lang->code }}" rows="4">{{ old('about_main_header_paragraph.' . $lang->code, $translation->about_main_header_paragraph ?? '') }}</textarea>
                                                </div>

                                                <hr>
                                                <div class="form-group">
                                                    <label for="why_choose_title_[{{ $lang->code }}]" class="font-weight-bold">Why Choose Title ([{{ $lang->name }}])</label>
                                                    <input type="text" name="why_choose_title[{{ $lang->code }}]" class="form-control shadow-sm" id="why_choose_title_[{{ $lang->code }}]" value="{{ old('why_choose_title.' . $lang->code ,$translation->why_choose_title??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="why_choose_content_{{ $lang->code }}" class="font-weight-bold">Why Choose Content ({{ $lang->name }})</label>
                                                    <textarea name="why_choose_content[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="why_choose_content_{{ $lang->code }}" rows="4">{{ old('why_choose_content.' . $lang->code, $translation->why_choose_content ?? '') }}</textarea>
                                                </div>


                                                <hr>
                                                <div class="form-group">
                                                    <label for="our_vision_[{{ $lang->code }}]" class="font-weight-bold">Our Vision Title ([{{ $lang->name }}])</label>
                                                    <input type="text" name="our_vision[{{ $lang->code }}]" class="form-control shadow-sm" id="our_vision_[{{ $lang->code }}]" value="{{ old('our_vision.' . $lang->code ,$translation->our_vision??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="our_vision_content_{{ $lang->code }}" class="font-weight-bold">Our Vision Content ({{ $lang->name }})</label>
                                                    <textarea name="our_vision_content[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="our_vision_content_{{ $lang->code }}" rows="4">{{ old('our_vision_content.' . $lang->code, $translation->our_vision_content ?? '') }}</textarea>
                                                </div>

                                                <hr>
                                                <div class="form-group">
                                                    <label for="our_mission_[{{ $lang->code }}]" class="font-weight-bold">Our Mission Title ([{{ $lang->name }}])</label>
                                                    <input type="text" name="our_mission[{{ $lang->code }}]" class="form-control shadow-sm" id="our_mission_[{{ $lang->code }}]" value="{{ old('our_mission.' . $lang->code ,$translation->our_mission??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="our_mission_content_{{ $lang->code }}" class="font-weight-bold">Our Mission Content ({{ $lang->name }})</label>
                                                    <textarea name="our_mission_content[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="our_mission_content_{{ $lang->code }}" rows="4">{{ old('our_mission_content.' . $lang->code, $translation->our_mission_content ?? '') }}</textarea>
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
                                                                    {{ old('meta_title.' . $lang->code, $translation->robots_index ?? '') === 'index' ? 'checked' : '' }}>
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
                                                                    {{ old('meta_title.' . $lang->code, $translation->robots_follow ?? '') === 'follow' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="robots_follow_{{ $lang->code }}">Follow</label>
                                                            </div>
                                                        </div>
                                                    </div>
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
