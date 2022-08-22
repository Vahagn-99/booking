<div class="modal-header">
    <h5 class="modal-title">{!! __('messages.Date editing') !!}</h5>
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
        <hr>
        <div class="row mx-0">
            <div class="col-12 px-0">
                <p class="error-message text-danger" id="err"></p>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#reservationNewWrapper">{!! __('messages.Add reservation') !!}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#closeDatesNewWrapper">{!! __('messages.Close dates') !!}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane container active" id="reservationNewWrapper">
                        <div class="card-body">
                            <div class="container-fluid px-0">
                                <form action="{{ route('admin/save-reservation') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="added_by_agency" value="{{ $added_by_agency }}">
                                    <input type="hidden" name="property" value="{{ $property->id }}">
                                    <input type="hidden" name="reservation_type" value="">
                                    <h5 class="mb-0 total float-right text-primary"></h5>
                                    <div class="form-group">
                                        <label for="reservation_check_in">{!! __('messages.Check in') !!}</label>
                                        <input type="text" name="reservation_check_in" value="{{ old('reservation_check_in') ? old('reservation_check_in') : '' }}" class="form-control @error('reservation_check_in') is-invalid @enderror datepicker" readonly id="reservation_check_in">
                                        @error('reservation_check_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="reservation_check_out">{!! __('messages.Check out') !!}</label>
                                        <input type="text" name="reservation_check_out" value="{{ old('reservation_check_out') ? old('reservation_check_out') : '' }}" class="form-control @error('reservation_check_out') is-invalid @enderror datepicker" readonly id="reservation_check_out">
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
                                                <option value="{{ $room->id }}" {{ old('reservation_room_type') && old('reservation_room_type') == $room->id ? 'selected' : '' }}>{!! $room->bed_name() !!}</option>
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
                                        <input type="text" name="contact_first_name" value="{{ old('contact_first_name') ? old('contact_first_name') : '' }}" class="form-control @error('contact_first_name') is-invalid @enderror" id="contact_first_name">
                                        @error('contact_first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_last_name">{!! __('messages.Last name') !!}</label>
                                        <input type="text" name="contact_last_name" value="{{ old('contact_last_name') ? old('contact_last_name') : '' }}" class="form-control @error('contact_last_name') is-invalid @enderror" id="contact_last_name">
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
                                                        <option value="{{ $i }}" {{ old('reservation_adults') && old('reservation_adults') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="reservation-children" class="property-list-el-wrapper font-weight-bold">{!! __('messages.Children') !!}</label>
                                                <select class="form-control form-data optional" name="reservation_children" id="reservation-children">
                                                    <option selected value="0">0</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_email">{!! __('messages.Email') !!}</label>
                                        <input type="text" name="contact_email" value="{{ old('contact_email') ? old('contact_email') : '' }}" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email">
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
                                            <option value="Paid" {{ old('payment_status') && old('payment_status') == "Paid" ? 'selected' : '' }}>{!! __('messages.Paid') !!}</option>
                                            <option value="Not Paid" {{ old('payment_status') && old('payment_status') == "Not Paid" ? 'selected' : '' }}>{!! __('messages.Not paid') !!}</option>
                                            <option value="Downpayment is paid" {{ old('payment_status') && old('payment_status') == "Downpayment is paid" ? 'selected' : '' }}>{!! __('messages.Downpayment is paid') !!}</option>
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
                                            <option value="EUR" {{ old('reservation_currency') && old('reservation_currency') == "EUR" ? 'selected' : '' }}>EUR</option>
                                            <option value="USD" {{ old('reservation_currency') && old('reservation_currency') == "USD" ? 'selected' : '' }}>US Dollar</option>
                                            <option value="RUB" {{ old('reservation_currency') && old('reservation_currency') == "RUB" ? 'selected' : '' }}>Russian Rouble</option>
                                            <option value="CAD" {{ old('reservation_currency') && old('reservation_currency') == "CAD" ? 'selected' : '' }}>Canadian Dollar</option>
                                            <option value="BRL" {{ old('reservation_currency') && old('reservation_currency') == "BRL" ? 'selected' : '' }}>Brazilian Real</option>
                                            <option value="GBP" {{ old('reservation_currency') && old('reservation_currency') == "GBP" ? 'selected' : '' }}>GBP</option>
                                        </select>
                                        @error('reservation_currency')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="reservation_rental_price">{!! __('messages.Subtotal') !!}</label>
                                        <input type="text" name="reservation_rental_price" value="{{ old('reservation_rental_price') ? old('reservation_rental_price') : '' }}" readonly class="form-control" id="reservation_rental_price">
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_amount">{!! __('messages.Discount') !!}</label>
                                        <input type="text" name="discount_amount" onchange="calcTotal(this)" value="{{ old('discount_amount') ? old('discount_amount') : '' }}" class="form-control @error('discount_amount') is-invalid @enderror" id="discount_amount">
                                        @error('discount_amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tax_fee">{!! __('messages.Tax & Fee') !!}</label>
                                        <input type="text" name="tax_fee" value="{{ old('tax_fee') ? old('tax_fee') : '' }}" readonly class="form-control" id="tax_fee">
                                    </div>
                                    <div class="form-group">
                                        <label for="total_price">{!! __('messages.Total price') !!}</label>
                                        <input type="text" name="total_price" value="{{ old('total_price') ? old('total_price') : '' }}" readonly class="form-control @error('total_price') is-invalid @enderror" id="total_price">
                                        @error('total_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="paid">{!! __('messages.Paid') !!}</label>
                                        <input type="text" name="paid" value="{{ old('paid') ? old('paid') : '' }}" class="form-control @error('paid') is-invalid @enderror" id="paid">
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
                                        <input type="text" name="contact_phone" value="{{ old('contact_phone') ? old('contact_phone') : '' }}" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone">
                                        @error('contact_phone')
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
                                        <label for="contact_state">{!! __('messages.State') !!}</label>
                                        <input type="text" name="contact_state" value="{{ old('contact_state') ? old('contact_state') : '' }}" class="form-control @error('contact_state') is-invalid @enderror" id="contact_state">
                                        @error('contact_state')
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
                                    <div class="form-group">
                                        <label for="contact_zip">{!! __('messages.Zip') !!}</label>
                                        <input type="text" name="contact_zip" value="{{ old('contact_country') ? old('contact_zip') : '' }}" class="form-control @error('contact_zip') is-invalid @enderror" id="contact_zip">
                                        @error('contact_zip')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="comment">{!! __('messages.Comment') !!}</label>
                                        <textarea rows="3" name="comment" class="form-control @error('comment') is-invalid @enderror" id="comment">{{ old('comment') ? old('comment') : '' }}</textarea>
                                        @error('comment')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group text-right">
                                        <button class="btn btn-primary" type="button" onclick="validateDates(this)">{!! __('messages.Add reservation') !!}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container fade" id="closeDatesNewWrapper">
                        <div class="card-body">
                            <div class="container-fluid px-0">
                                <form action="{{ route('admin/save-reservation') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="added_by_agency" value="{{ $added_by_agency }}">
                                    <input type="hidden" name="property" value="{{ $property->id }}">
                                    <input type="hidden" name="reservation_type" value="block">
                                    <h5 class="mb-0 total float-right text-primary"></h5>
                                    <div class="form-group">
                                        <label for="reservation_check_in_close">{!! __('messages.Check in') !!}</label>
                                        <input type="text" name="reservation_check_in" value="{{ old('reservation_check_in') ? old('reservation_check_in') : '' }}" class="form-control @error('reservation_check_in') is-invalid @enderror datepicker" readonly id="reservation_check_in_close">
                                        @error('reservation_check_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="reservation_check_out_close">{!! __('messages.Check out') !!}</label>
                                        <input type="text" name="reservation_check_out" value="{{ old('reservation_check_out') ? old('reservation_check_out') : '' }}" class="form-control @error('reservation_check_out') is-invalid @enderror datepicker" readonly id="reservation_check_out_close">
                                        @error('reservation_check_out')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="comment">{!! __('messages.Comment') !!}</label>
                                        <textarea rows="3" name="comment" class="form-control @error('comment') is-invalid @enderror" id="comment">{{ old('comment') ? old('comment') : '' }}</textarea>
                                        @error('comment')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group text-right">
                                        <button class="btn btn-primary" type="button" onclick="validateDates(this)">{!! __('messages.Close dates') !!}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$('input[name=reservation_check_in').datepicker({
    format: 'dd.mm.yyyy',
    datesDisabled: {!! $property->reservedDates(0) !!},
    startDate: new Date(),
    autoclose: true
}).on('changeDate', function(e) {
    calcTotal(e);
}).val("{{ $check_in }}");
$('#reservation_check_out').datepicker({
    format: 'dd.mm.yyyy',
    datesDisabled: {!! $property->reservedDates(0) !!},
    startDate: "{{ $startCheckOut }}",
    autoclose: true
}).on('changeDate', function(e) {
    calcTotal(e);
});
$('input[name=reservation_check_in_close').datepicker({
    format: 'dd.mm.yyyy',
    datesDisabled: {!! $property->reservedDates(0) !!},
    startDate: new Date(),
    autoclose: true
}).on('changeDate', function(e) {
    calcTotal(e);
}).val("{{ $check_in }}");
$('#reservation_check_out_close').datepicker({
    format: 'dd.mm.yyyy',
    datesDisabled: {!! $property->reservedDates(0) !!},
    startDate: "{{ $check_in }}",
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
$(document).ready(function () {
    var checkin = document.getElementById("reservation_check_in").value;
    var checkout = document.getElementById("reservation_check_out").value;
    var checkinclose = document.getElementById("reservation_check_in_close").value;
    var checkoutclose = document.getElementById("reservation_check_out_close").value;

    $("#reservation_check_in").blur(function(){
        document.getElementById("reservation_check_in").value = checkin;
    });
    $("#reservation_check_out").blur(function(){
        document.getElementById("reservation_check_out").value = checkout;
    });
    $("#reservation_check_in_close").blur(function(){
        document.getElementById("reservation_check_in_close").value = checkinclose;
    });
    $("#reservation_check_out_close").blur(function(){
        document.getElementById("reservation_check_out_close").value = checkoutclose;
    });z
});
</script>
