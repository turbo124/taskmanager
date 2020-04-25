@component('email.template.master', ['design' => 'light'])

    @slot('header')
        @component('email.components.header', ['p' => '', 'logo' => $url])
            @lang('texts.download')
        @endcomponent

    @endslot

    @slot('footer')
        @component('email.components.footer')
           Thanks
        @endcomponent
    @endslot

@endcomponent