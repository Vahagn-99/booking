<div class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-ajax">
            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('save-item') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="hash" value="{{ $hash }}">
                    <input type="hidden" name="model_name" value="{{ $model_name }}">
                    <input type="hidden" name="property" value="{{ $property->id }}">
                    <input type="hidden" name="id" value="{{ $model_id }}">

                    <div class="form-group">
                        <label for="model_name">{!! __('messages.Name') !!}</label>
                        <input type="text" name="item_name" value="{{ old('item_name') ? old('item_name') : ($model ? $model->name : '') }}" class="form-control @error('name') is-invalid @enderror" id="model_name">
                        @error('item_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_bed">{!! __('messages.Minimum bedrooms') !!}</label>
                                <input type="number" name="min_bed" value="{{ old('min_bed') ? old('min_bed') : ($model ? $model->min_bed : '') }}" class="form-control @error('min_bed') is-invalid @enderror" id="min_bed">
                                @error('min_bed')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_stay">{!! __('messages.Minimum stay') !!}</label>
                                <input type="number" name="min_stay" value="{{ old('min_stay') ? old('min_stay') : ($model ? $model->min_stay : '') }}" class="form-control @error('min_stay') is-invalid @enderror" id="min_stay">
                                @error('min_stay')
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
                                <label for="start_date">{!! __('messages.Start date') !!}</label>
                                <input type="text" name="start_date" value="{{ old('start_date') ? old('start_date') : ($model ? $model->formated_start : '') }}" class="datepicker form-control @error('start_date') is-invalid @enderror" id="start_date" readonly>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">{!! __('messages.End date') !!}</label>
                                <input type="text" name="end_date" value="{{ old('end_date') ? old('end_date') : ($model ? $model->formated_end : '') }}" class="datepicker form-control @error('end_date') is-invalid @enderror" id="end_date" readonly>
                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price">{!! __('messages.Base Rate') !!}</label>
                        <input type="number" name="price" value="{{ old('price') ? old('price') : ($model ? $model->price : '') }}" class="form-control @error('price') is-invalid @enderror" id="price">
                        @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {
    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        startDate: new Date(),
        datesDisabled: {!! $model ? $property->periodDisabled($model->id) : $property->periodDisabled(0) !!},
        autoclose: true
    });
    $('#start_date').datepicker({
        format: 'dd.mm.yyyy',
        startDate: new Date(),
        datesDisabled: {!! $model ? $property->periodDisabled($model->id) : $property->periodDisabled(0) !!},
        autoclose: true
    }).on('changeDate', function(e) {
        $('#end_date').datepicker('setStartDate',e.date);
    });
    if ($('.modal-ajax').find('#model_name').val() == '') {
        $('.modal-ajax').find('.btn-success').prop('disabled',true);
    }
    $('.modal-ajax').find('#model_name').on('keyup keydown keypress', function(e){
        var button = $(this).closest('.modal-ajax').find('.btn-success');
        if ($(this).val() != '') {
            button.prop('disabled',false);
        } else {
            button.prop('disabled',true);
        }
    })
    $('.modal-ajax').find('form').on('keyup keydown keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    var checkinclose = document.getElementById("start_date").value;
    var checkoutclose = document.getElementById("end_date").value;

    $("#start_date").blur(function(){
        document.getElementById("start_date").value = checkin;
    });
    $("#end_date").blur(function(){
        document.getElementById("end_date").value = checkout;
    });
});
</script>
