<div class="modal fade" id="choosePropertiesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{!! __('messages.Choose properties to show in calendar') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form action="{{ url()->full() }}" method="get">
                <div class="modal-body">
                    <div class="container-fluid px-0">
                        <div class="row mx-0">
                            <div class="col-12 px-0">
                                <h5>{!! __('messages.Chosen properties') !!}</h5>
                            </div>
                            <div class="col-12 px-0">
                                <div class="over-block-large">
                                    @foreach ($user->properties()->get() as $p)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="properties[]" value="{{ $p->id }}" id="propeCal{{ $p->id }}" {{ \Request::has('properties') && in_array($p->id, \Request::get('properties')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="propeCal{{ $p->id }}">{!! $p->name !!}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">{!! __('messages.Show') !!}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>
