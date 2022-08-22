<div class="row">
    <div class="col-sm-12">
        <form action="{{ route('save-commission') }}" method="post">
            @csrf
            <input type="hidden" name="property" value="{{ $property->id }}">
            <input type="hidden" name="agency" value="{{ $user->id }}">
            <input type="hidden" name="hash" value="">
            <div class="form-group">
                <label for="commission">{!! __('messages.Agency commission') !!} (%)</label>
                <input type="text" name="commission" value="{{ old('commission') ? old('commission') : ($property->commissions->where('agency',$user->id)->first() ? $property->commissions->where('agency',$user->id)->first()->commission : '10') }}" class="form-control @error('commission') is-invalid @enderror" id="commission">
                @error('commission')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="default_currency">{!! __('messages.Currency') !!}</label>
            <select disabled class="form-control @error('default_currency') is-invalid @enderror" id="default_currency">
                <option value="">{!! __('messages.Choose') !!}...</option>
                <option value="EUR" {{ old('default_currency') && old('default_currency') == "EUR" ? 'selected' : ($property && $property->default_currency == "EUR" ? 'selected' : '') }}>EUR</option>
                <option value="USD" {{ old('default_currency') && old('default_currency') == "USD" ? 'selected' : ($property && $property->default_currency == "USD" ? 'selected' : '') }}>US Dollar</option>
                <option value="RUB" {{ old('default_currency') && old('default_currency') == "RUB" ? 'selected' : ($property && $property->default_currency == "RUB" ? 'selected' : '') }}>Russian Rouble</option>
                <option value="CAD" {{ old('default_currency') && old('default_currency') == "CAD" ? 'selected' : ($property && $property->default_currency == "CAD" ? 'selected' : '') }}>Canadian Dollar</option>
                <option value="BRL" {{ old('default_currency') && old('default_currency') == "BRL" ? 'selected' : ($property && $property->default_currency == "BRL" ? 'selected' : '') }}>Brazilian Real</option>
                <option value="GBP" {{ old('default_currency') && old('default_currency') == "GBP" ? 'selected' : ($property && $property->default_currency == "GBP" ? 'selected' : '') }}>GBP</option>
            </select>
            @error('default_currency')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="base_rate">{!! __('messages.Base Rate') !!}</label>
            <input type="text" disabled value="{{ old('base_rate') ? old('base_rate') : ($property ? $property->base_rate : '') }}" class="form-control @error('base_rate') is-invalid @enderror" id="base_rate">
            @error('base_rate')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="base_rate_kind">{!! __('messages.Base Rate Kind') !!}</label>
            <select disabled class="form-control @error('base_rate_kind') is-invalid @enderror" id="base_rate_kind">
                <option value="">{!! __('messages.Choose') !!}...</option>
                <option value="Nightly" {{ old('base_rate_kind') && old('base_rate_kind') == "Nightly" ? 'selected' : ($property && $property->base_rate_kind == "Nightly" ? 'selected' : '') }}>{!! __('messages.Nightly') !!}</option>
                <option value="Weekly" {{ old('base_rate_kind') && old('base_rate_kind') == "Weekly" ? 'selected' : ($property && $property->base_rate_kind == "Weekly" ? 'selected' : '') }}>{!! __('messages.Weekly') !!}</option>
            </select>
            @error('base_rate_kind')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="min_stay_base">{!! __('messages.Minimum stay') !!}</label>
            <input type="number" disabled value="{{ old('min_stay_base') ? old('min_stay_base') : ($property ? $property->min_stay_base : '') }}" class="form-control @error('min_stay_base') is-invalid @enderror" id="min_stay_base">
            @error('min_stay_base')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="min_bed">{!! __('messages.Minimum bedrooms') !!}</label>
            <input type="number" disabled value="{{ old('min_bed') ? old('min_bed') : ($property ? $property->min_bed : '') }}" class="form-control @error('min_bed') is-invalid @enderror" id="min_bed">
            @error('min_bed')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="minimum_rate">{!! __('messages.Minimum Rate') !!}</label>
            <input type="text" disabled value="{{ old('minimum_rate') ? old('minimum_rate') : ($property ? $property->minimum_rate : '') }}" class="form-control @error('minimum_rate') is-invalid @enderror" id="minimum_rate">
            @error('minimum_rate')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="downpayment">{!! __('messages.Downpayment') !!} (%)</label>
            <input type="text" disabled value="{{ old('downpayment') ? old('downpayment') : ($property ? $property->downpayment : '') }}" class="form-control @error('downpayment') is-invalid @enderror" id="downpayment">
            @error('downpayment')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="damage_deposit">{!! __('messages.Damage Deposit') !!}</label>
            <input type="text" disabled value="{{ old('damage_deposit') ? old('damage_deposit') : ($property ? $property->damage_deposit : '') }}" class="form-control @error('damage_deposit') is-invalid @enderror" id="damage_deposit">
            @error('damage_deposit')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="city_tax">{!! __('messages.City Tax') !!} (%)</label>
            <input type="text" disabled value="{{ old('city_tax') ? old('city_tax') : ($property ? $property->city_tax : '') }}" class="form-control @error('city_tax') is-invalid @enderror" id="city_tax">
            @error('city_tax')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="service_charge">{!! __('messages.Service Charge') !!} (%)</label>
            <input type="text" disabled value="{{ old('service_charge') ? old('service_charge') : ($property ? $property->service_charge : '') }}" class="form-control @error('service_charge') is-invalid @enderror" id="service_charge">
            @error('service_charge')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="cleaning_fee">{!! __('messages.Cleaning fee') !!}</label>
            <input type="text" disabled value="{{ old('cleaning_fee') ? old('cleaning_fee') : ($property ? $property->cleaning_fee : '') }}" class="form-control @error('cleaning_fee') is-invalid @enderror" id="cleaning_fee">
            @error('cleaning_fee')
                <span class="invalid-feedback" role="alert">
                    <strong>{!! $message !!}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<!-- additional prices -->
<hr>
<h5>{!! __('messages.Price periods') !!} {!! __('messages.Base') !!}</h5>
@if ($property && $property->periods->count() > 0)
    <div class="table-responsive">
        <table class="table table-stripped">
            <thead>
                <tr>
                    <th>{!! __('messages.Name') !!}</th>
                    <th>{!! __('messages.Minimum bedrooms') !!}</th>
                    <th>{!! __('messages.Start date') !!}</th>
                    <th>{!! __('messages.End date') !!}</th>
                    <th>{!! __('messages.Minimum stay') !!}</th>
                    <th>{!! __('messages.Price') !!}</th>
                    <th>{!! __('messages.Price with agency commission') !!}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($property->periods as $period)
                    <tr>
                        <td>{!! $period->name !!}</td>
                        <td>{{ $period->min_bed }}</td>
                        <td>{{ $period->formated_start }}</td>
                        <td>{{ $period->formated_end }}</td>
                        <td>{{ $period->min_stay }}</td>
                        <td>{{ \App\Models\Currency::priceFormat((float)$period->price) }}</td>
                        @if ($property->commissions->where('agency',$user->id)->first())
                            <td>{{ \App\Models\Currency::priceFormat((float)$period->price * (1 + (float)$property->commissions->where('agency',$user->id)->first()->commission / 100)) }}</td>
                        @else
                            <td>{{ \App\Models\Currency::priceFormat((float)$period->price * 1.1) }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
<hr>
<h5>{!! __('messages.Discounts') !!}</h5>
@if ($property && $property->discounts->count() > 0)
    <div class="table-responsive">
        <table class="table table-stripped">
            <thead>
                <tr>
                    <th>{!! __('messages.Name') !!}</th>
                    <th>{!! __('messages.Start date') !!}</th>
                    <th>{!! __('messages.End date') !!}</th>
                    <th>{!! __('messages.Discount type') !!}</th>
                    <th>{!! __('messages.Discount value') !!}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($property->discounts as $discount)
                    <tr>
                        <td>{!! $discount->name !!}</td>
                        <td>{{ $discount->formated_start }}</td>
                        <td>{{ $discount->formated_end }}</td>
                        <td>{{ $discount->type }}</td>
                        <td>{{ $discount->value }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success open-modal"
                                data-property="{{ $property ? $property->id :'' }}" data-title="{!! __('messages.Edit discount') !!}"
                                data-model_name="Discounts" data-modal="_discount" data-model_id="{{ $discount->id }}"
                                ><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-danger open-modal"
                                data-title="{!! __('messages.Remove discount') !!}" data-property="{{ $property ? $property->id :'' }}" data-modal="_delete"
                                data-model_name="Discounts" data-model_id="{{ $discount->id }}"
                                ><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
<hr>
<h5>{!! __('messages.Price for bedrooms') !!}</h5>
@if ($property && $property->propertybedrooms->count() > 0)
    @foreach ($property->propertybedrooms as $pbed)
        <div class="list-group mb-3">
            <div class="list-group-item list-group-item-action active">
                <div class="float-left">
                    <h5>{{ $pbed->bed_name() }}</h5>
                </div>
            </div>
            @if ($pbed->pricebedrooms->count() > 0)
                <div class="list-group-item list-group-item-action">
                    <div class="table-responsive">
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>{!! __('messages.Name') !!}</th>
                                    <th>{!! __('messages.Start date') !!}</th>
                                    <th>{!! __('messages.End date') !!}</th>
                                    <th>{!! __('messages.Minimum stay') !!}</th>
                                    <th>{!! __('messages.Price') !!}</th>
                                    <th>{!! __('messages.Price with agency commission') !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pbed->pricebedrooms as $pricebedroom)
                                    <tr>
                                        <td>{!! $pricebedroom->name !!}</td>
                                        <td>{{ $pricebedroom->formated_start }}</td>
                                        <td>{{ $pricebedroom->formated_end }}</td>
                                        <td>{{ $pricebedroom->min }}</td>
                                        <td>{{ \App\Models\Currency::priceFormat((float)$pricebedroom->price) }}</td>
                                        @if ($property->commissions->where('agency',$user->id)->first())
                                            <td>{{ \App\Models\Currency::priceFormat((float)$pricebedroom->price * (1 + (float)$property->commissions->where('agency',$user->id)->first()->commission / 100)) }}</td>
                                        @else
                                        <td>{{ \App\Models\Currency::priceFormat((float)$pricebedroom->price * 1.1) }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
@endif
<hr>
<div class="form-group">
    <label for="pbedrooms_rule">{!! __('messages.Price for bedrooms rules') !!}</label>
    <p>{!! old('pbedrooms_rule') ? old('pbedrooms_rule') : ($property ? $property->pbedrooms_rule : '') !!}</p>
</div>
