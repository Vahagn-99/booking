<div class="modal fade" id="property-description-show" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{!! __('messages.Property description') !!}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="property-description-subtitle">
                    <span class="pre-wrap d-block">{!! session()->get('locale') == 'fr' ? $property->full_desc_fr : $property->full_desc_en !!}</span>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="property-cancellation-show" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{!! __('messages.Cancellation') !!}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="property-list-el-wrapper property-description-subtitle"><b>{!! __('messages.Cancellation') !!}: </b>
                    <span class="pre-wrap d-block">{!! session()->get('locale') == 'fr' ? $property->full_canc_fr : $property->full_canc_en !!}</span>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="property-agreement-show" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{!! __('messages.Agreement') !!}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="property-list-el-wrapper pre-wrap">{!! session()->get('locale') == 'fr' ? $property->full_agreement_fr : $property->full_agreement_en !!}</p>
            </div>
        </div>
    </div>
</div>
