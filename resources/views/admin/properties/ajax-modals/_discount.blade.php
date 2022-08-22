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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">{!! __('messages.Discount type') !!}</label>
                                <select name="type" class="form-control @error('type') is-invalid @enderror" id="type">
                                    <option value="Percentage" {{ old('type') && old('type') == "Percentage" ? 'selected' : ($model && $model->type == "Percentage" ? 'selected' : '') }}>{!! __('messages.Percentage') !!}</option>
                                    <option value="Money" {{ old('type') && old('type') == "Money" ? 'selected' : ($model && $model->type == "Money" ? 'selected' : '') }}>{!! __('messages.Money') !!}</option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-">
                            <div class="form-group">
                                <label for="value">{!! __('messages.Discount value') !!}</label>
                                <input type="number" name="value" value="{{ old('value') ? old('value') : ($model ? $model->value : '') }}" class="form-control @error('value') is-invalid @enderror" id="value">
                                @error('value')
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
        datesDisabled: {!! $model ? $property->discountDisabled($model->id) : $property->discountDisabled(0) !!},
        autoclose: true
    });
    $('#start_date').datepicker({
        format: 'dd.mm.yyyy',
        startDate: new Date(),
        datesDisabled: {!! $model ? $property->discountDisabled($model->id) : $property->discountDisabled(0) !!},
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
});
</script>
