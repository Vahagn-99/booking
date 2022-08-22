@extends('adminlte::page')

@section('title', ' | ' . __('messages.Reservations'))

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Reservations') . ' - <small>' . $property->name . '</small>' !!}
        <a href="{{ route('admin/calendar', ['id' => $property->id]) }}" class="float-right btn btn-sm btn-success" title="{!! __('messages.Open individual calendar') !!}"><i class="far fa-calendar-alt"></i> {!! __('messages.Open calendar') !!}</a>
    </h1>
@stop

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <button type="button" id="search_button" class="btn btn-primary">{!! __('messages.Search') !!}</button>
        <a href="{{ url()->current() }}" class="btn btn-warning">{!! __('messages.Reset') !!}</a>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url()->current() . '?all' }}" class="btn btn-primary">{!! __('messages.Show all (reservations and block)') !!}</a>
        <a href="{{ url()->current() }}" class="btn btn-warning">{!! __('messages.Show reservations only') !!}</a>
    </div>
</div>
<div class="table-grid">
    {!! grid_view([
            'dataProvider' => $dataProvider,
            'columnFields' => [
                [
                    'attribute' => 'id',
                    'label' => __('messages.Unique ID'),
                ],
                [
                    'attribute' => 'payment_status',
                    'label' => __('messages.Status'),
                ],
                [
                    'attribute' => 'comment',
                    'label' => __('messages.Comment'),
                ],
                [
                    'attribute' => 'contact_first_name',
                    'label' => __('messages.Customer'),
                    'value' => function ($row) {
                        return $row->contact_first_name .' '. $row->contact_last_name;
                    },
                ],
                [
                    'attribute' => 'contact_email',
                    'label' => __('messages.CustomerEmail'),
                    'value' => function ($row) {
                        return $row->contact_email ;
                    },
                ],
                 [
                    'attribute' => 'contact_phone',
                    'label' => __('messages.CustomerPhone'),
                    'value' => function ($row) {
                        return $row->contact_phone ;
                    },
                ],
                [
                    'attribute' => 'reservation_check_in',
                    'label' => __('messages.Dates'),
                    'value' => function ($row) {
                        return '<small>' . $row->reservation_check_in .' - '. $row->reservation_check_out . '</small>';
                    },
                    'format' => [
                        'class' => Itstructure\GridView\Formatters\HtmlFormatter::class,
                    ]
                ],
                [
                    'attribute' => 'reservation_room_type',
                    'label' => __('messages.Rooms Count'),
                ],
                [
                    'attribute' => 'total_price',
                    'label' => __('messages.Total'),
                    'value' => function ($row) {
                        return floatval($row->total_price) . ' ' . $row->reservation_currency;
                    },
                ],
                [
                    'label' => 'Details',
                    'value' => function ($row) {
                        if (File::exists(public_path($row->contract_link))) {
                            return '<a class="btn btn-warning" href="'.asset($row->contract_link).'" target="_blank">"' . __('messages.View Contract') . '"</a>';
                        }
                        return '';
                    },
                    'format' => [
                        'class' => Itstructure\GridView\Formatters\HtmlFormatter::class,
                    ]
                ],
                [
                    'label' => '',
                    'class' => Itstructure\GridView\Columns\ActionColumn::class,
                    'actionTypes' => [
                        [
                            'class' => Itstructure\GridView\Actions\Delete::class,
                            'url' => function ($data) {
                                return $data->id;
                            }
                        ]
                    ]
                ]
            ]
        ])
    !!}
</div>
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
                    <input type="hidden" name="id" value="" id="data_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('messages.Close') !!}</button>
                    <button type="submit" class="btn btn-danger">{!! __('messages.Remove') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (\Request::has('all'))
    <script type="text/javascript">
        $('#grid_view_filters_form').append('<input type="hidden" name="all" value="1">');
        $('.page-link').each(function () {
            let href = $(this).attr('href'); $(this).attr('href',href+'&all');
        })
    </script>
@endif

@stop
