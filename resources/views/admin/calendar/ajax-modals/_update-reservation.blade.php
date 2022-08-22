<form action="{{ route('admin/save-reservation') }}" method="post">
    @csrf
    <input type="hidden" name="added_by_agency" value="{{ $added_by_agency }}">
    <input type="hidden" name="property" value="{{ $property->id }}">
    <input type="hidden" name="id" value="{{ $reservation->id }}">
    <input type="hidden" name="reservation_type" value="{{ $reservation->reservation_type }}">

    <div class="modal-header">
        <h4 class="modal-title">{!! $reservation->reservation_type == 'block' ? __('messages.Closed dates') : ( $reservation->service == '' || $reservation->service == null ? __('messages.Reservation') . ' #' . $reservation->id . ' <br><small>(from Booking FWI)</small>' : ($reservation->service == 'BookingCom' ? __('messages.Reservation') . ' #' . $reservation->id . ' <br><small>(from BookingCom)</small>' : __('messages.Reservation') . ' #' . $reservation->id . ' <br><small>(from Owner)</small>')) !!}</h4>
        <button type="button" class="close" onclick="closeSidebar()">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="container-fluid px-0">
            <div class="row mx-0 align-items-center">
                <div class="col-12">
                    <h5>{!! __('messages.Property') !!}: <small><a href="{{ route('admin/property-settings', ['id' => $property->id]) }}" target="_blank">{!! $property->name !!}</a></small></h5>
                </div>
            </div>
            <p class="error-message text-danger" id="err"></p>
            <hr>
            <h5 class="mb-0 total float-right text-primary"></h5>
            <div class="form-group">
                <label for="reservation_check_in">{!! __('messages.Check in') !!}</label>
                <input type="text" name="reservation_check_in" value="{{ old('reservation_check_in') ? old('reservation_check_in') : $reservation->reservation_check_in }}" class="form-control @error('reservation_check_in') is-invalid @enderror datepicker" readonly id="reservation_check_in">
                @error('reservation_check_in')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="reservation_check_out">{!! __('messages.Check out') !!}</label>
                <input type="text" name="reservation_check_out" value="{{ old('reservation_check_out') ? old('reservation_check_out') : $reservation->reservation_check_out }}" class="form-control @error('reservation_check_out') is-invalid @enderror datepicker" readonly id="reservation_check_out">
                @error('reservation_check_out')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="reservation_room_type">{!! __('messages.Room type') !!}</label>
                <select name="reservation_room_type" onchange="calcTotal(this)" class="form-control @error('reservation_room_type') is-invalid @enderror" id="reservation_room_type">
                    <option value="">{{ $property->min_bed }} {!! $property->min_bed > 1 ? __('messages.Bedrooms') : __('messages.Bedroom') !!}</option>
                    @foreach ($property->propertybedrooms as $room)
                        <option value="{{ $room->id }}" {{ old('reservation_room_type') && old('reservation_room_type') == $room->id ? 'selected' : ($reservation && $reservation->reservation_room_type == $room->id ? 'selected' : '') }}>{!! $room->bed_name() !!}</option>
                    @endforeach
                </select>
                @error('reservation_room_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_first_name">{!! __('messages.First name') !!}</label>
                <input type="text" name="contact_first_name" value="{{ old('contact_first_name') ? old('contact_first_name') : $reservation->contact_first_name }}" class="form-control @error('contact_first_name') is-invalid @enderror" id="contact_first_name">
                @error('contact_first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_last_name">{!! __('messages.Last name') !!}</label>
                <input type="text" name="contact_last_name" value="{{ old('contact_last_name') ? old('contact_last_name') : $reservation->contact_last_name }}" class="form-control @error('contact_last_name') is-invalid @enderror" id="contact_last_name">
                @error('contact_last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <hr>
            <h5 class="mb-4">{!! __('messages.Reservation info') !!}</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="reservation-adults" class="property-list-el-wrapper font-weight-bold">{!! __('messages.Adults') !!}</label>
                        <select class="form-control form-data optional @error('reservation_adults') is-invalid @enderror" name="reservation_adults" data-maxpeople="{{ $property->sleeps_max }}" id="reservation-adults">
                            @for ($i=0; $i <= $property->sleeps_max; $i++)
                                <option value="{{ $i }}" {{ old('reservation_adults') && old('reservation_adults') == $i ? 'selected' : ($reservation->reservation_adults == $i ? 'selected' : '') }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="reservation-children" class="property-list-el-wrapper font-weight-bold">{!! __('messages.Children') !!}</label>
                        <select class="form-control form-data optional" name="reservation_children" id="reservation-children">
                            <option selected value="{{ $reservation->reservation_children }}">{{ $reservation->reservation_children }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="contact_email">{!! __('messages.Email') !!}</label>
                <input type="text" name="contact_email" value="{{ old('contact_email') ? old('contact_email') : $reservation->contact_email }}" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email">
                @error('contact_email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <hr>
            <h5 class="mb-4">{!! __('messages.Payment') !!}</h5>
            <div class="form-group">
                <label for="payment_status">{!! __('messages.Payment status') !!}</label>
                <select name="payment_status" onchange="calcTotal(this)" class="form-control @error('payment_status') is-invalid @enderror" id="payment_status">
                    <option value="">{!! __('messages.Not set') !!}</option>
                    <option value="Paid" {{ old('payment_status') && old('payment_status') == "Paid" ? 'selected' : ($reservation && $reservation->payment_status == "Paid" ? 'selected' : '') }}>{!! __('messages.Paid') !!}</option>
                    <option value="Not Paid" {{ old('payment_status') && old('payment_status') == "Not Paid" ? 'selected' : ($reservation && $reservation->payment_status == "Not Paid" ? 'selected' : '') }}>{!! __('messages.Not paid') !!}</option>
                    <option value="Downpayment is paid" {{ old('payment_status') && old('payment_status') == "Downpayment is paid" ? 'selected' : ($reservation && $reservation->payment_status == "Downpayment is paid" ? 'selected' : '') }}>{!! __('messages.Downpayment is paid') !!}</option>
                </select>
                @error('payment_status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="reservation_currency">{!! __('messages.Currency') !!}</label>
                <select name="reservation_currency" onchange="calcTotal(this)" class="form-control @error('reservation_currency') is-invalid @enderror" id="reservation_currency">
                    <option value="EUR" {{ old('reservation_currency') && old('reservation_currency') == "EUR" ? 'selected' : ($reservation && $reservation->reservation_currency == "EUR" ? 'selected' : '') }}>EUR</option>
                    <option value="USD" {{ old('reservation_currency') && old('reservation_currency') == "USD" ? 'selected' : ($reservation && $reservation->reservation_currency == "USD" ? 'selected' : '') }}>US Dollar</option>
                    <option value="RUB" {{ old('reservation_currency') && old('reservation_currency') == "RUB" ? 'selected' : ($reservation && $reservation->reservation_currency == "RUB" ? 'selected' : '') }}>Russian Rouble</option>
                    <option value="CAD" {{ old('reservation_currency') && old('reservation_currency') == "CAD" ? 'selected' : ($reservation && $reservation->reservation_currency == "CAD" ? 'selected' : '') }}>Canadian Dollar</option>
                    <option value="BRL" {{ old('reservation_currency') && old('reservation_currency') == "BRL" ? 'selected' : ($reservation && $reservation->reservation_currency == "BRL" ? 'selected' : '') }}>Brazilian Real</option>
                    <option value="GBP" {{ old('reservation_currency') && old('reservation_currency') == "GBP" ? 'selected' : ($reservation && $reservation->reservation_currency == "GBP" ? 'selected' : '') }}>GBP</option>
                </select>
                @error('reservation_currency')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="reservation_rental_price">{!! __('messages.Subtotal') !!}</label>
                <input type="text" name="reservation_rental_price" value="{{ old('reservation_rental_price') ? old('reservation_rental_price') : $reservation->reservation_rental_price }}" readonly class="form-control" id="reservation_rental_price">
            </div>
            <div class="form-group">
                <label for="discount_amount">{!! __('messages.Discount') !!}</label>
                <input type="text" name="discount_amount" onchange="calcTotal(this)" value="{{ old('discount_amount') ? old('discount_amount') : $reservation->discount_amount }}" class="form-control @error('discount_amount') is-invalid @enderror" id="discount_amount">
                @error('discount_amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="tax_fee">{!! __('messages.Tax & Fee') !!}</label>
                <input type="text" name="tax_fee" value="{{ old('tax_fee') ? old('tax_fee') : $reservation->tax_fee }}" readonly class="form-control" id="tax_fee">
            </div>
            <div class="form-group">
                <label for="total_price">{!! __('messages.Total price') !!}</label>
                <input type="text" name="total_price" value="{{ old('total_price') ? old('total_price') : $reservation->total_price }}" readonly class="form-control @error('total_price') is-invalid @enderror" id="total_price">
                @error('total_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="paid">{!! __('messages.Paid') !!}</label>
                <input type="text" name="paid" value="{{ old('paid') ? old('paid') : $reservation->paid }}" class="form-control @error('paid') is-invalid @enderror" id="paid">
                @error('paid')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <hr>
            <h5 class="mb-4">{!! __('messages.Person info') !!}</h5>
            <div class="form-group">
                <label for="contact_phone">{!! __('messages.Phone') !!}</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') ? old('contact_phone') : $reservation->contact_phone }}" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone">
                @error('contact_phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_country">{!! __('messages.Country') !!}</label>
                <input type="text" name="contact_country" value="{{ old('contact_country') ? old('contact_country') : $reservation->contact_country }}" class="form-control @error('contact_country') is-invalid @enderror" id="contact_country">
                @error('contact_country')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_state">{!! __('messages.State') !!}</label>
                <input type="text" name="contact_state" value="{{ old('contact_state') ? old('contact_state') : $reservation->contact_state }}" class="form-control @error('contact_state') is-invalid @enderror" id="contact_state">
                @error('contact_state')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_city">{!! __('messages.City') !!}</label>
                <input type="text" name="contact_city" value="{{ old('contact_city') ? old('contact_city') : $reservation->contact_city }}" class="form-control @error('contact_city') is-invalid @enderror" id="contact_city">
                @error('contact_city')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_address">{!! __('messages.Address') !!}</label>
                <input type="text" name="contact_address" value="{{ old('contact_address') ? old('contact_address') : $reservation->contact_address }}" class="form-control @error('contact_address') is-invalid @enderror" id="contact_address">
                @error('contact_address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_zip">{!! __('messages.Zip') !!}</label>
                <input type="text" name="contact_zip" value="{{ old('contact_country') ? old('contact_zip') : $reservation->contact_zip }}" class="form-control @error('contact_zip') is-invalid @enderror" id="contact_zip">
                @error('contact_zip')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="comment">{!! __('messages.Comment') !!}</label>
                <textarea rows="3" name="comment" class="form-control @error('comment') is-invalid @enderror" id="comment">{{ old('comment') ? old('comment') : $reservation->comment }}</textarea>
                @error('comment')
                    <span class="invalid-feedback" role="alert">
                        <strong>{!! $message !!}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="button" onclick="validateDates(this)">{!! __('messages.Save') !!}</button>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#removeEl" data-dismiss="modal">{!! __('messages.Delete') !!}</button>
    </div>
</form>

<div class="modal fade" id="removeEl" tabindex="-1" aria-labelledby="removeElLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="removeElLabel">{!! __('messages.Remove reservation') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-reservation') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <h5>{!! __('messages.Are you sure you want to delete this reservation') !!}?</h5>
                    <input type="hidden" name="id" value="{{ $reservation->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-danger">{!! __('messages.Remove') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$('.datepicker').datepicker({
    format: 'dd.mm.yyyy',
    datesDisabled: {!! $property->reservedDates($reservation->id) !!},
    startDate: new Date(),
    autoclose: true
}).on('changeDate', function(e) {
    calcTotal(e);
});
// Check maximum people on the property page
$("#reservation-adults").on('change', function () {
    if(parseInt($("#reservation-adults").attr('data-maxpeople')) - parseInt($("#reservation-adults").val()) <= 0){
        $("#reservation-children").attr('disabled', true);
        $("#reservation-children").empty();
        $("#reservation-children").append('<option selected value="0">0</option>');
    }else{
        $("#reservation-children").attr('disabled', false);
        $("#reservation-children").empty();
        for(let x = 0; x <= (parseInt($("#reservation-adults").attr('data-maxpeople')) - parseInt($("#reservation-adults").val())); x++){
            if(x == 0){
                $("#reservation-children").append('<option selected value="' + x + '">' + x + '</option>');
            }else{
                $("#reservation-children").append('<option value="' + x + '">' + x + '</option>');
            }
        }
    }
});
</script>
