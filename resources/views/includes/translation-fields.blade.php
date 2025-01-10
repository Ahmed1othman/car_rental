@foreach($activeLanguages as $language)
    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
         id="{{ $language->code }}" 
         role="tabpanel" 
         aria-labelledby="{{ $language->code }}-tab">
        
        <div class="form-group">
            <label for="title_{{ $language->code }}">Title ({{ strtoupper($language->code) }})</label>
            <input type="text" 
                   class="form-control @error('title.' . $language->code) is-invalid @enderror" 
                   id="title_{{ $language->code }}" 
                   name="title[{{ $language->code }}]" 
                   value="{{ old('title.' . $language->code, $model->translate($language->code)->title ?? '') }}">
            @error('title.' . $language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="article_{{ $language->code }}">Article ({{ strtoupper($language->code) }})</label>
            <textarea class="form-control editor @error('article.' . $language->code) is-invalid @enderror" 
                      id="article_{{ $language->code }}" 
                      name="article[{{ $language->code }}]" 
                      rows="5">{{ old('article.' . $language->code, $model->translate($language->code)->article ?? '') }}</textarea>
            @error('article.' . $language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- SEO Fields -->
        <div class="form-group">
            <label for="meta_title_{{ $language->code }}">Meta Title ({{ strtoupper($language->code) }})</label>
            <input type="text" 
                   class="form-control @error('meta_title.' . $language->code) is-invalid @enderror" 
                   id="meta_title_{{ $language->code }}" 
                   name="meta_title[{{ $language->code }}]" 
                   value="{{ old('meta_title.' . $language->code, $model->translate($language->code)->meta_title ?? '') }}">
            @error('meta_title.' . $language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_description_{{ $language->code }}">Meta Description ({{ strtoupper($language->code) }})</label>
            <textarea class="form-control @error('meta_description.' . $language->code) is-invalid @enderror" 
                      id="meta_description_{{ $language->code }}" 
                      name="meta_description[{{ $language->code }}]" 
                      rows="3">{{ old('meta_description.' . $language->code, $model->translate($language->code)->meta_description ?? '') }}</textarea>
            @error('meta_description.' . $language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_keywords_{{ $language->code }}">Meta Keywords ({{ strtoupper($language->code) }})</label>
            <input type="text" 
                   class="form-control @error('meta_keywords.' . $language->code) is-invalid @enderror" 
                   id="meta_keywords_{{ $language->code }}" 
                   name="meta_keywords[{{ $language->code }}]" 
                   value="{{ old('meta_keywords.' . $language->code, $model->translate($language->code)->meta_keywords ?? '') }}">
            @error('meta_keywords.' . $language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endforeach
