@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
     <p>Dear Booking FWI,<br> The new blocking dates was added by {{ $user->fullName() }} for property {{ $reservation->propertyInfo->name }}</p>
     <p>The blocking dates was added for these dates: {{ $reservation->reservation_check_in }} - {{ $reservation->reservation_check_out }}</p>
     <p>The blocking dates was created on {{ date("d.m.Y h:i a") }}</p>

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
