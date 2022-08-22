<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#en_agreement">{!! __('messages.En') !!}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#fr_agreement">{!! __('messages.Fr') !!}</a>
    </li>
</ul>
<div class="tab-content pt-3">
    <div id="en_agreement" class="tab-pane active">
        <div class="form-group">
            <label for="agreement_en">{!! __('messages.Rental Agreement') !!}</label>
            <textarea name="agreement_en" rows="8" class="form-control @error('agreement_en') is-invalid @enderror" id="agreement_en">{!! old('agreement_en') ? old('agreement_en') : ($property ? $property->agreement_en : '') !!}</textarea>
            @error('agreement_en')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div id="fr_agreement" class="tab-pane fade">
        <div class="form-group">
            <label for="agreement_fr">{!! __('messages.Rental Agreement') !!}</label>
            <textarea name="agreement_fr" rows="8" class="form-control @error('agreement_fr') is-invalid @enderror" id="agreement_fr">{!! old('agreement_fr') ? old('agreement_fr') : ($property ? $property->agreement_fr : '') !!}</textarea>
            @error('agreement_fr')
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
