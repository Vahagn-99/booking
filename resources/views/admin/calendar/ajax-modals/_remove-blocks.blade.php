<div class="modal-header">
    <h5 class="modal-title">{!! __('messages.Remove Closed Dates') !!}</h5>
    <button type="button" class="close" onclick="closeSidebar()">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="container-fluid px-0">
        <div class="row mx-0">
            <div class="col-12 px-0">
                <p class="error-message text-danger" id="err"></p>
                <div class="card-body">
                    <div class="container-fluid px-0">
                        <form action="{{ route('calendar/remove-block') }}" method="post">
                            @csrf
                            <input type="hidden" name="property" value="{{ $property->id }}">
                            <h5 class="mb-0 total float-right text-primary"></h5>
                            <div class="form-group">
                                <label for="date_from_close">{!! __('messages.Date from') !!}</label>
                                <input type="text" name="date_from" value="{{ old('date_from') ? old('date_from') : '' }}" class="form-control @error('date_from') is-invalid @enderror datepicker" readonly id="date_from_close">
                                @error('date_from')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="date_to_close">{!! __('messages.Date to') !!}</label>
                                <input type="text" name="date_to" value="{{ old('date_to') ? old('date_to') : '' }}" class="form-control @error('date_to') is-invalid @enderror datepicker" readonly id="date_to_close">
                                @error('date_to')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-primary" type="submit">{!! __('messages.Remove Closed Dates') !!}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$('.datepicker').datepicker({
    format: 'dd.mm.yyyy',
    autoclose: true
})
</script>
