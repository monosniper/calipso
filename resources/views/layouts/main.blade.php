<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    @livewireStyles
{{--    <link rel="stylesheet" href="{{ asset('assets/css/simple-grid.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-grid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-all.min.css') }}"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />

    @yield('head')

    @translations
@yield('css')


</head>
<body>
@sectionMissing('header')
@include('includes.header', ['dark' => true, 'normalize' => true])
    @else
        @yield('header')
    @endif

    @yield('content')

    @include('includes.footer')

    @include('includes.cookies')

{{--    @include('includes.snow')--}}

    <!-- Scripts -->
    @livewireScripts
<script src="https://api-maps.yandex.ru/2.1/?apikey=96e40232-a3b1-4f7d-9fca-b0e0233d4364&lang={{app()->getLocale()}}_US" type="text/javascript">
</script>

{{-- Pusher js --}}
{{--<script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>--}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <script>
        // Basket & wishlist
        function playAnimation(target, animation) {
            $(target)
                .addClass('active')
                .addClass('animate__animated')
                .addClass(`animate__${animation}`);

            setTimeout(function () {
                $(target)
                    .removeClass('animate__animated')
                    .removeClass(`animate__${animation}`);
            }, 500);
        }

        window.addEventListener('item-added', event => {
            playAnimation($(`#${event.detail.instance} i`), 'rubberBand');
        })
    </script>
    @yield('js')
    @include('includes.noty-messages')
    @include('includes.fondy')
</body>
</html>
