@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
     <p>Dear Booking FWI, <br>You just received a message from {!! $name !!}</p>
     <p>The message has come from website {{ config('app.url') }}</p>
     <p>The person email: {{ $email }}</p>
     <p>The person message: {!! $message !!}</p>
     <p><small>The message was sent on {{ date("d.m.Y h:i a") }}.</small></p>

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent
