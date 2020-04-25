   {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            @if(isset($data['logo']))
                <img src="{{ $data['logo'] }}" style="height: 6rem;">
            @endif;
        @endcomponent
    @endslot

@component('mail::message')
# {{ $data['title'] }}
  
{{ $data['message'] }}
   
@if(isset($data['url']) && isset($data['button_text']))
    @component('mail::button', ['url' => $data['url']])
        {{ $data['button_text'] }}
    @endcomponent
@endif

@if(isset($data['signature']))
    @slot('signature')
    <img style="display:block; width:100px;height:100px;" id="base64image" src="{{$data['signature']}}"/>
    @endslot
@endif
   
Thanks,<br>

  {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot

@endcomponent
