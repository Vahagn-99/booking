@extends('layouts.app-inner')

@section('content')
<div class="container-fluid pt-5 pb-3">
    <div class="text-center mb-4">
        <h2><i class="far fa-clock"></i></h2>
        <h4>{!! __('messages.Office Hours (Caribbean Time)') !!}</h4>
        @if($adminData->work_schedule_type == 'same')
            <p>Monday - Friday | {!!$adminData->mon_work_time !!}</p>
            @if($adminData->sun_work_time)
                <p>Sunday | {!! $adminData->sun_work_time !!}</p>
            @endif
            @if($adminData->sat_work_time)
                <p>Saturday | {!! $adminData->sat_work_time !!}</p>
            @endif
        @else
            @if($adminData->mon_work_time)
                <p>Monday | {!! $adminData->mon_work_time !!}</p>
            @endif
            @if($adminData->tue_work_time)
                <p>Tuesday | {!! $adminData->tue_work_time !!}</p>
            @endif
            @if($adminData->wed_work_time)
                <p>Wednesday | {!! $adminData->wed_work_time !!}</p>
            @endif
            @if($adminData->thu_work_time)
                <p>Thursday | {!! $adminData->thu_work_time !!}</p>
            @endif
            @if($adminData->fri_work_time)
                <p>Friday | {!! $adminData->fri_work_time !!}</p>
            @endif
            @if($adminData->sat_work_time)
                <p>Saturday | {!! $adminData->sat_work_time !!}</p>
            @endif
            @if($adminData->sun_work_time)
                <p>Sunday | {!! $adminData->sun_work_time !!}</p>
            @endif
        @endif
        <p><i class="fas fa-map-marker-alt"></i><span class="pl-2">{!! $adminData->fullAddress() !!}</span></p>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{!! __('messages.Thank you for your message!') !!}</strong> {{ session('success') }}
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            @foreach ($errors->all() as $error)
                {{ $error }}<br/>
            @endforeach
        </div>
   @endif
    <div class="row mb-5">
        <div class="col-md-6">
            <div id="mapContact"></div>
        </div>
        <div class="col-md-6">
            <h1 class="mb-0 h3">{!! __('messages.Contact information') !!}</h1>
            <ul class="list-group list-group-flush mt-3">
                @if($adminData->phone != "")
                    <li class="list-group-item pl-0"><i class="fas fa-phone pr-3"></i><span>{{ $adminData->phone }}</span></li>
                @endif
                @if($adminData->mobile != "")
                    <li class="list-group-item pl-0"><i class="fas fa-mobile-alt pr-3"></i><span>{{ $adminData->mobile }}</span></li>
                @endif
                @if($adminData->fax != "")
                    <li class="list-group-item pl-0"><i class="fas fa-fax pr-3"></i><span>{{ $adminData->fax }}</span></li>
                @endif
                @if($adminData->email_business != "")
                    <li class="list-group-item pl-0"><i class="far fa-envelope pr-3"></i><span>{{ $adminData->email_business }}</span></li>
                @endif
            </ul>
            @if (isset($sitefooter) && ($sitefooter->facebook_link != "" || $sitefooter->twitter_link != "" || $sitefooter->pinterest_link != "" || $sitefooter->instagram_link != ""))
                <ul id="contact-page-social" class="pl-0">
                    @if ($sitefooter->facebook_link != "")
                        <li><a href="{{ $sitefooter->facebook_link }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                    @endif
                    @if ($sitefooter->twitter_link != "")
                        <li><a href="{{ $sitefooter->twitter_link }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
                    @endif
                    @if ($sitefooter->pinterest_link != "")
                        <li><a href="{{ $sitefooter->pinterest_link }}" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>
                    @endif
                    @if ($sitefooter->instagram_link != "")
                        <li><a href="{{ $sitefooter->instagram_link }}" target="_blank"><i class="fab fa-instagram"></i></a></li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <div class="col-6 offset-3 p-4">
        <h3>{!! __('messages.Contact us') !!}</h3>
        <h5 class="font-weight-normal">{!! __('messages.If you have something nice to say, want to tell us something we can improve or if you have a question, write to us') !!}</h5>
        {!! RecaptchaV3::initJs() !!}
        <form method="post" action="{{ route('send-contact') }}">
            @csrf
            <div class="form-group">
                <label>{!! __('messages.Full name') !!}</label>
                <input type="text" name="name" class="form-control" value="" />
                @if ($errors->has('name'))
                    <span class="err-message">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label>{!! __('messages.Email') !!}</label>
                <input type="text" name="email" class="form-control" value="" />
                @if ($errors->has('email'))
                    <span class="err-message">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label>{!! __('messages.Message') !!}</label>
                <textarea name="message" class="form-control" rows="5"></textarea>
                @if ($errors->has('message'))
                    <span class="err-message">
                        <strong>{{ $errors->first('message') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                {!! RecaptchaV3::field('register') !!}
                @if ($errors->has('g-recaptcha-response'))
                    <div class="err-message">
                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-main" value="{!! __('messages.Send message') !!}" />
            </div>
        </form>
    </div>
</div>
@stop
