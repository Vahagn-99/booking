<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#en">{!! __('messages.En') !!}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#fr">{!! __('messages.Fr') !!}</a>
    </li>
</ul>
<div class="tab-content pt-3">
    <div id="en" class="tab-pane active">
        <div class="form-group">
            <label for="headline_en">{!! __('messages.Headline') !!}</label>
            <input type="text" name="headline_en" value="{!! old('headline_en') ? old('headline_en') : ($property ? $property->headline_en : '') !!}" class="form-control @error('headline_en') is-invalid @enderror" id="headline_en">
            @error('headline_en')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="summary_en">{!! __('messages.Summary') !!}</label>
            <input type="text" name="summary_en" value="{!! old('summary_en') ? old('summary_en') : ($property ? $property->summary_en : '') !!}" class="form-control @error('summary_en') is-invalid @enderror" id="summary_en">
            @error('summary_en')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="description_en">{!! __('messages.Description') !!}</label>
            <textarea name="description_en" rows="8" class="form-control @error('description_en') is-invalid @enderror" id="description_en">{!! old('description_en') ? old('description_en') : ($property ? $property->description_en : '') !!}</textarea>
            @error('description_en')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div id="fr" class="tab-pane fade">
        <div class="form-group">
            <label for="headline_fr">{!! __('messages.Headline') !!}</label>
            <input type="text" name="headline_fr" value="{!! old('headline_fr') ? old('headline_fr') : ($property ? $property->headline_fr : '') !!}" class="form-control @error('headline_fr') is-invalid @enderror" id="headline_fr">
            @error('headline_fr')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="summary_fr">{!! __('messages.Summary') !!}</label>
            <input type="text" name="summary_fr" value="{!! old('summary_fr') ? old('summary_fr') : ($property ? $property->summary_fr : '') !!}" class="form-control @error('summary_fr') is-invalid @enderror" id="summary_fr">
            @error('summary_fr')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="description_fr">{!! __('messages.Description') !!}</label>
            <textarea name="description_fr" rows="8" class="form-control @error('description_fr') is-invalid @enderror" id="description_fr">{!! old('description_fr') ? old('description_fr') : ($property ? $property->description_fr : '') !!}</textarea>
            @error('description_fr')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
</div>
