@php
/** @var string $field */
/** @var mixed $value */
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <input type="checkbox" class="form-control form-control-sm" name="{{ $field }}[]" value="{{ $value }}" role="grid-view-checkbox-item" />
    </div>
</div>