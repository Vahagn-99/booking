<div class="form-group">
    <label for="seo_title">{!! __('messages.Meta tag title') !!}</label>
    <input type="text" name="seo_title" value="{!! old('seo_title') ? old('seo_title') : ($property && $property->seo ? $property->seo->seo_title : '') !!}" class="form-control @error('seo_title') is-invalid @enderror" id="seo_title">
    @error('seo_title')
        <span class="invalid-feedback" role="alert">
            <strong>{!! $message !!}</strong>
        </span>
    @enderror
</div>
<div class="form-group">
    <label for="seo_description">{!! __('messages.Meta tag description') !!}</label>
    <textarea name="seo_description" rows="8" class="form-control @error('seo_description') is-invalid @enderror" id="seo_description">{!! old('seo_description') ? old('seo_description') : ($property && $property->seo ? $property->seo->seo_description : '') !!}</textarea>
    @error('seo_description')
        <span class="invalid-feedback" role="alert">
            <strong>{!! $message !!}</strong>
        </span>
    @enderror
</div>
<div class="form-group">
    <label for="seo_key">{!! __('messages.Keywords') !!}</label>
    <input type="text" name="seo_key" value="{!! old('seo_key') ? old('seo_key') : ($property && $property->seo ? $property->seo->seo_key : '') !!}" class="form-control @error('seo_key') is-invalid @enderror" id="seo_key">
    @error('seo_key')
        <span class="invalid-feedback" role="alert">
            <strong>{!! $message !!}</strong>
        </span>
    @enderror
    <small class="form-text text-muted">{!! __('messages.Please, divide keywords with comma.') !!}</small>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
</div>
