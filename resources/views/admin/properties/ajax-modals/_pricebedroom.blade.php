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
                    <input type="hidden" name="bed_id" value="{{ $bed_id }}">
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
                                <label for="start">{!! __('messages.Start date') !!}</label>
                                <input type="text" name="start" value="{{ old('start') ? old('start') : ($model ? $model->formated_start : '') }}" class="datepicker form-control @error('start') is-invalid @enderror" id="start" readonly>
                                @error('start')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end">{!! __('messages.End date') !!}</label>
                                <input type="text" name="end" value="{{ old('end') ? old('end') : ($model ? $model->formated_end : '') }}" class="datepicker form-control @error('end') is-invalid @enderror" id="end" readonly>
                                @error('end')
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
                                <label for="min">{!! __('messages.Minimum stay') !!}</label>
                                <input type="number" name="min" value="{{ old('min') ? old('min') : ($model ? $model->min : '') }}" class="form-control @error('min') is-invalid @enderror" id="min">
                                @error('min')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
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
        datesDisabled: {!! $model ? $property->periodBedroomDisabled($bed_id,$model_id) : $property->periodBedroomDisabled($bed_id,0) !!},
        autoclose: true
    });
    $('#start').datepicker({
        format: 'dd.mm.yyyy',
        startDate: new Date(),
        datesDisabled: {!! $model ? $property->periodBedroomDisabled($bed_id,$model_id) : $property->periodBedroomDisabled($bed_id,0) !!},
        autoclose: true
    }).on('changeDate', function(e) {
        $('#end').datepicker('setStartDate',e.date);
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
});
</script>
