@extends('layouts.app-inner')

@section('content')

<div class="container-fluid" id="locations-body">
    <div class="row">
          <!-- location properties -->
        @include('site.partials.location-filter')
    </div>
</div>

@stop

@section('scripts')
    <script type="text/javascript">
        // $(document).on('click', '.page-link', function(event){
        //     event.preventDefault();
        //     var page = $(this).attr('href').split('page=')[1];
        //     loadProperties(page);
        //  });
        loadProperties(1);
    </script>
@stop
