<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#en_cancellation">{!! __('messages.En') !!}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#fr_cancellation">{!! __('messages.Fr') !!}</a>
    </li>
</ul>
<div class="tab-content pt-3">
    <div id="en_cancellation" class="tab-pane active">
        <div class="form-group">
            <label for="cancellation_en">{!! __('messages.Rental Cancellation Policy') !!}</label>
            <textarea name="cancellation_en" rows="8" class="form-control @error('cancellation_en') is-invalid @enderror" id="cancellation_en">{!! old('cancellation_en') ? old('cancellation_en') : ($property ? $property->cancellation_en : '') !!}</textarea>
            @error('cancellation_en')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div id="fr_cancellation" class="tab-pane fade">
        <div class="form-group">
            <label for="cancellation_fr">{!! __('messages.Rental Cancellation Policy') !!}</label>
            <textarea name="cancellation_fr" rows="8" class="form-control @error('cancellation_fr') is-invalid @enderror" id="cancellation_fr">{!! old('cancellation_fr') ? old('cancellation_fr') : ($property ? $property->cancellation_fr : '') !!}</textarea>
            @error('cancellation_fr')
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
<hr>
<div class="row mb-3">
    <div class="col-md-6">
        <h5>{!! __('messages.Cancellation items') !!}</h5>
        <button type="button" class="btn btn-sm btn-primary open-modal my-2"
                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Add cancellation item') !!}"
                data-model_name="Cancellation" data-modal="_cancellation" data-model_id="" {{ !$property ? 'disabled' : '' }}
                >{!! __('messages.Add cancellation item') !!}</button>
        @if ($property && $property->cancellations->count() > 0)
            <div class="list-group">
                @foreach ($property->cancellations as $c)
                    <div class="list-group-item list-group-item-action">
                        <div class="float-left">
                            <p>{!! __('messages.Penalty percentage') !!} (%): <i>{{ $c->penalty }}</i></p>
                            <p>{!! __('messages.Eligible days') !!}: <i>{{ $c->eligible }}</i></p>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-sm btn-success open-modal"
                                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Edit cancellation item') !!}"
                                data-model_name="Cancellation" data-modal="_cancellation" data-model_id="{{ $c->id }}"
                                ><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-danger open-modal"
                                data-title="{!! __('messages.Remove cancellation item') !!}" data-modal="_delete"
                                data-model_name="Cancellation" data-model_id="{{ $c->id }}"
                                ><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
