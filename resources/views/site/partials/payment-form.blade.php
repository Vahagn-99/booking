<form action="/save-reservation" method="post" name="payformname" class="payment-form">
    @csrf
    <input type="hidden" name="fromsite" value="{{ $agencydomain ? $agencydomain : 'mainsite' }}">
    <input type="hidden" name="property" value="{{ $property->id }}">
    <input type="hidden" name="reservation_check_in" value="{{ $data['reservation_check_in'] }}">
    <input type="hidden" name="reservation_check_out" value="{{ $data['reservation_check_out'] }}">
    <input type="hidden" name="reservation_adults" value="{{ $data['reservation_adults'] }}">
    <input type="hidden" name="reservation_children" value="{{ isset($data['reservation_children']) ? $data['reservation_children'] : 0 }}">
    <input type="hidden" name="reservation_room_type" value="{{ $data['room_id'] }}">
    <input type="hidden" name="reservation_currency" value="{{ \Cookie::has('currency') ? \Cookie::get('currency') : $property->currency }}">
    <input type="hidden" name="reservation_rental_price" value="{{ $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['subtotal'] }}">
    <input type="hidden" name="discount_amount" value="">
    <input type="hidden" name="tax_fee" value="{{ $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['tax_fee'] }}">
    <input type="hidden" name="total_price" value="{{ $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['total'] }}">
    <input type="hidden" name="paid" value="{{ Carbon\Carbon::today()->diffInDays(Carbon\Carbon::createFromFormat('d.m.y',$data['reservation_check_in'])->format('Y-m-d') ) > 30 ? $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['subtotal'] * $property->downpayment/100 : $property->price_quote($data['reservation_check_in'], $data['reservation_check_out'], $data['room_id'])['total'] }}">

    <div id="pay-form" class="">
        <h3>{!! __('messages.Personal information') !!}</h3>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="contact_first_name">{!! __('messages.First name') !!}<span class="form-required-element pl-1">*</span></label>
                    <input type="text" name="contact_first_name" value="{{ old('contact_first_name') ? old('contact_first_name') : '' }}" class="form-control @error('contact_first_name') is-invalid @enderror" id="contact_first_name">
                    @error('contact_first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_email">{!! __('messages.Email') !!}<span class="form-required-element pl-1">*</span></label>
                    <input type="text" name="contact_email" value="{{ old('contact_email') ? old('contact_email') : '' }}" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email">
                    @error('contact_email')<h3>{!! __('messages.Personal information') !!}</h3>

                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_country">{!! __('messages.Country') !!}</label>
                    <input type="text" name="contact_country" value="{{ old('contact_country') ? old('contact_country') : '' }}" class="form-control @error('contact_country') is-invalid @enderror" id="contact_country">
                    @error('contact_country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_city">{!! __('messages.City') !!}</label>
                    <input type="text" name="contact_city" value="{{ old('contact_city') ? old('contact_city') : '' }}" class="form-control @error('contact_city') is-invalid @enderror" id="contact_city">
                    @error('contact_city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_address">{!! __('messages.Address') !!}</label>
                    <input type="text" name="contact_address" value="{{ old('contact_address') ? old('contact_address') : '' }}" class="form-control @error('contact_address') is-invalid @enderror" id="contact_address">
                    @error('contact_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="contact_last_name">{!! __('messages.Last name') !!}<span class="form-required-element pl-1">*</span></label>
                    <input type="text" name="contact_last_name" value="{{ old('contact_last_name') ? old('contact_last_name') : '' }}" class="form-control @error('contact_last_name') is-invalid @enderror" id="contact_last_name">
                    @error('contact_last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_phone">{!! __('messages.Phone') !!}</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') ? old('contact_phone') : '' }}" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone">
                    @error('contact_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_state">{!! __('messages.State/Province') !!}</label>
                    <input type="text" name="contact_state" value="{{ old('contact_state') ? old('contact_state') : '' }}" class="form-control @error('contact_state') is-invalid @enderror" id="contact_state">
                    @error('contact_state')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_zip">{!! __('messages.Zip code') !!}</label>
                    <input type="text" name="contact_zip" value="{{ old('contact_country') ? old('contact_zip') : '' }}" class="form-control @error('contact_zip') is-invalid @enderror" id="contact_zip">
                    @error('contact_zip')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <p class="text-danger" id="p-error"></p>
        <div class="form-group text-right">
            <button class="btn btn-primary w-100" id="pay-button" type="button">{!! __('messages.Pay') !!}</button>
        </div>
    </div>
    <div id="pay-method" class="payment-methods d-none">
        {{-- <div class="mb-4 mx-auto text-center"><h2 class="display-6">{!! __('messages.Choose payment method') !!}</h2></div> --}}
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="bg-white shadow-sm pt-4 pl-2 pr-2 pb-2">
                            <ul role="tablist" class="nav bg-light nav-pills rounded nav-fill mb-3">
                                <li class="nav-item"><a data-toggle="pill" href="#credit-card" class="nav-link active"><i class="fas fa-credit-card mr-2"></i> {!! __('messages.Credit Card') !!}</a></li>
                                <!-- <li class="nav-item"> <a data-toggle="pill" href="#paypal" class="nav-link "> <i class="fab fa-paypal mr-2"></i> Paypal</a></li> -->
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div id="credit-card" class="tab-pane fade show active pt-3">
                                <input type="hidden" name="payment_method" value="Stripe">
                                <div class="form-group"><label for="cardNumber"><h6>{!! __('messages.Card number') !!}</h6></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" required size="19" maxlength="19"  data-stripe="number" name="cardnumber" placeholder="{!! __('messages.Credit Card Number') !!}" value="{{ old('cardnumber') }}">
                                        @if ($errors->has('cardnumber'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('cardnumber') }}</strong>
                                            </span>
                                        @endif
                                        <div class="input-group-append"> <span class="input-group-text text-muted"> <i class="fab fa-cc-visa mx-1"></i> <i class="fab fa-cc-mastercard mx-1"></i> <i class="fab fa-cc-amex mx-1"></i> </span> </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label><span class="hidden-xs"><h6>{!! __('messages.Expiration Date') !!}</h6></span></label>
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="cardexpmonth" size="2" maxlength="2" class="card-date" value="{{ old('cardexpmonth') }}" placeholder="{!! __('messages.MM') !!}" required>
                                                <input class="form-control" type="text" name="cardexpyear" size="2" maxlength="2" data-stripe="exp_year" id="cyear" value="{{ old('cardexpyear') }}" placeholder="{!! __('messages.YY') !!}" required>
                                                <div class="num-help">
                                                    @if ($errors->has('cardexpyear'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('cardexpyear') }}</strong>
                                                        </span>
                                                    @endif
                                                    @if ($errors->has('cardexpmonth'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('cardexpmonth') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group mb-4">
                                            <label data-toggle="tooltip" title="Three digit CV code on the back of your card">
                                                <h6>{!! __('messages.CVV') !!} <i class="fa fa-question-circle d-inline"></i></h6>
                                            </label>
                                            <input type="text" class="form-control cardcvvinput" size="4" maxlength="4" data-stripe="cvc" placeholder="{!! __('messages.CVC') !!}" name="cardcvc" value="{{ old('cardcvc') }}">
                                            @if ($errors->has('cardcvc'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('cardcvc') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="subscribe btn btn-primary btn-block shadow-sm">{!! __('messages.Confirm Payment') !!}</button>
                                </div>
                            </div>
                            <!-- <div id="paypal" class="tab-pane fade pt-3">
                                <div class="flex-center position-ref full-height">
                                  <div class="content">
                                      <a href="https://www.paypal.com/in/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/in/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-200px.png" border="0" alt="PayPal Logo"></a>
                                      <input type="hidden" name="payment_method" value="Paypal">

                                      <div class="card-footer">
                                          <button type="submit" class="subscribe btn btn-primary btn-block shadow-sm"> Confirm Payment </button>
                                      </div>
                                  </div>
                              </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
