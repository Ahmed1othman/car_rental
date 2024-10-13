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
                                                <!-- Video Preview with Placeholder -->
                                                <div class="mb-3">
                                                    <video id="videoPreview_hero_header_video_path" controls style="height: 300px; width: 100%; object-fit: cover; border: 2px solid #ddd;">
                                                        <source src="{{ $item->hero_header_video_path ? asset('storage/' . $item->hero_header_video_path) : 'https://via.placeholder.com/400' }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>

                                                <!-- File Input for Video Upload -->
                                                <div class="custom-file">
                                                    <input type="file" name="hero_header_video_path" class="custom-file-input video-upload @error('hero_header_video_path') is-invalid @enderror" id="video_path" data-preview="videoPreview_hero_header_video_path" accept="video/*">
                                                    <label class="custom-file-label" for="hero_header_video_path">Upload Hero Header Video</label>
                                                </div>

                                                <!-- Error Handling -->
                                                @error('hero_header_video_path')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cars">Select Cars</label>
                                        <select name="item_ids[]" id="cars" class="form-control" multiple>
                                            @foreach($cars as $car)
                                                <option value="{{ $car->id }}">
                                                    {{ $car->name }} - <img src="{{ asset($car->image_path) }}" width="50" height="30" alt="{{ $car->name }}">
                                                </option>
                                            @endforeach
                                        </select>
                                        <small>Select one or more cars to add to the section.</small>
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
                                                    <label for="hero_header_title_[{{ $lang->code }}]" class="font-weight-bold">Hero Header Title ({{ $lang->name }})</label>
                                                    <input type="text" name="hero_header_title[{{ $lang->code }}]" class="form-control shadow-sm" id="hero_header_title_[{{ $lang->code }}]" value="{{ old('hero_header_title.' . $lang->code ,$translation->hero_header_title??'') }}">
                                                </div>


                                                <div class="form-group">
                                                    <label for="car_only_section_title_[{{ $lang->code }}]" class="font-weight-bold">Car Only Section Title ({{ $lang->name }})</label>
                                                    <input type="text" name="car_only_section_title[{{ $lang->code }}]" class="form-control shadow-sm" id="car_only_section_title_[{{ $lang->code }}]" value="{{ old('car_only_section_title.' . $lang->code ,$translation->car_only_section_title??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="car_only_section_paragraph_{{ $lang->code }}" class="font-weight-bold">Car Only Section Paragraph ({{ $lang->name }})</label>
                                                    <textarea name="car_only_section_paragraph[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm " id="car_only_section_paragraph_{{ $lang->code }}" rows="4">{{ old('car_only_section_paragraph.' . $lang->code, $translation->car_only_section_paragraph ?? '') }}</textarea>
                                                </div>


                                                <div class="form-group">
                                                    <label for="special_offers_section_title_[{{ $lang->code }}]" class="font-weight-bold">Special Offers Section Title ({{ $lang->name }})</label>
                                                    <input type="text" name="special_offers_section_title[{{ $lang->code }}]" class="form-control shadow-sm" id="special_offers_section_title_[{{ $lang->code }}]" value="{{ old('special_offers_section_title.' . $lang->code ,$translation->special_offers_section_title??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="special_offers_section_paragraph_{{ $lang->code }}" class="font-weight-bold">Special Offers Section Paragraph ({{ $lang->name }})</label>
                                                    <textarea name="special_offers_section_paragraph[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="special_offers_section_paragraph_{{ $lang->code }}" rows="4">{{ old('special_offers_section_paragraph.' . $lang->code, $translation->special_offers_section_paragraph ?? '') }}</textarea>
                                                </div>


                                                <div class="form-group">
                                                    <label for="why_choose_us_section_title_[{{ $lang->code }}]" class="font-weight-bold">Why Choose Us Section Title ({{ $lang->name }})</label>
                                                    <input type="text" name="why_choose_us_section_title[{{ $lang->code }}]" class="form-control shadow-sm" id="why_choose_us_section_title_[{{ $lang->code }}]" value="{{ old('why_choose_us_section_title.' . $lang->code ,$translation->why_choose_us_section_title??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="why_choose_us_section_paragraph_{{ $lang->code }}" class="font-weight-bold">Why Choose Us Section Paragraph({{ $lang->name }})</label>
                                                    <textarea name="why_choose_us_section_paragraph[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="why_choose_us_section_paragraph_{{ $lang->code }}" rows="4">{{ old('why_choose_us_section_paragraph.' . $lang->code, $translation->why_choose_us_section_paragraph ?? '') }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="faq_section_title_[{{ $lang->code }}]" class="font-weight-bold">FAQ Section Title ({{ $lang->name }})</label>
                                                    <input type="text" name="faq_section_title[{{ $lang->code }}]" class="form-control shadow-sm" id="faq_section_title_[{{ $lang->code }}]" value="{{ old('faq_section_title.' . $lang->code ,$translation->faq_section_title??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="faq_section_paragraph_{{ $lang->code }}" class="font-weight-bold">FAQ Section Paragraph({{ $lang->name }})</label>
                                                    <textarea name="faq_section_paragraph[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="faq_section_paragraph_{{ $lang->code }}" rows="4">{{ old('faq_section_paragraph.' . $lang->code, $translation->faq_section_paragraph ?? '') }}</textarea>
                                                </div>


                                                <div class="form-group">
                                                    <label for="where_find_us_section_title_[{{ $lang->code }}]" class="font-weight-bold">Where Find Us Section Title ({{ $lang->name }})</label>
                                                    <input type="text" name="where_find_us_section_title[{{ $lang->code }}]" class="form-control shadow-sm" id="where_find_us_section_title_[{{ $lang->code }}]" value="{{ old('where_find_us_section_title.' . $lang->code ,$translation->where_find_us_section_title??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="where_find_us_section_paragraph_{{ $lang->code }}" class="font-weight-bold">Where Find Us Section Paragraph ({{ $lang->name }})</label>
                                                    <textarea name="where_find_us_section_paragraph[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="where_find_us_section_paragraph_{{ $lang->code }}" rows="4">{{ old('where_find_us_section_paragraph.' . $lang->code, $translation->where_find_us_section_paragraph ?? '') }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="required_documents_section_title_[{{ $lang->code }}]" class="font-weight-bold">Required Documents Section Title ({{ $lang->name }})</label>
                                                    <input type="text" name="required_documents_section_title[{{ $lang->code }}]" class="form-control shadow-sm" id="required_documents_section_title_[{{ $lang->code }}]" value="{{ old('required_documents_section_title.' . $lang->code ,$translation->required_documents_section_title??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="required_documents_section_paragraph_{{ $lang->code }}" class="font-weight-bold">Required Documents Section Paragraph ({{ $lang->name }})</label>
                                                    <textarea name="required_documents_section_paragraph[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="required_documents_section_paragraph_{{ $lang->code }}" rows="4">{{ old('required_documents_section_paragraph.' . $lang->code, $translation->required_documents_section_paragraph ?? '') }}</textarea>
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
        document.querySelectorAll('.video-upload').forEach(input => {
            input.addEventListener('change', function(event) {
                const previewId = this.getAttribute('data-preview');
                const file = event.target.files[0];
                if (file && file.type.includes('video')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const videoElement = document.getElementById(previewId);
                        videoElement.src = e.target.result;
                        videoElement.load();
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

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
