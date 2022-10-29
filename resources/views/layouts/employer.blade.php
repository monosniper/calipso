<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

    <title>{{$user->fullName}} - @yield('title') | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />

    <!-- Styles -->
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('assets/css/simple-grid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}"/>

    @translations

    @yield('css')


</head>
<body>
@sectionMissing('header')
    @include('includes.header', ['dark' => true, 'normalize' => true])
    @else
        @yield('header')
    @endif

    @php
        $menu_items = [
            [
                'route' => 'employer',
                'icon' => 'user',
                'name' => __('main.profile'),
            ],
            [
                'route' => 'employer.lots',
                'icon' => 'lots',
                'name' => __('main.lots'),
                'count' => $user->lots_count,
            ],
            [
                'route' => 'employer.orders',
                'icon' => 'order',
                'name' => __('main.orders'),
                'count' => $user->orders_count,
            ],
        ];
    @endphp

    <div class="container">

        <div class="profile_wrapper">
            <div class="profile_sidebar">
                <div class="grey_block grey_block_full grey_block_min_padding">
                    <div class="profile_menu">
                        @foreach ($menu_items as $item)
                            <a href="{{route('freelance.'.$item['route'], $user->id)}}" class="profile_menu_item {{url()->current() === route('freelance.'.$item['route'], $user->id) ? 'active' : ''}}">
                                <div class="profile_menu_item_icon">
                                    @include('includes.svg', ['name' => $item['icon']])
                                </div>
                                <div class="profile_menu_item_title">
                                    {{$item['name']}}
                                    @isset($item['counter'])
                                        @livewire($item['counter'])
                                    @endif
                                    <small>{{isset($item['count']) ? "(".$item['count'].")" : ''}}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <hr class="separate">
                    <div class="ad_block">
                        Ad
                        <br>
                        260x260
                    </div>
                    <div class="ad_block">
                        <!-- Рекламный блок в боковой панели -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:260px;height:260px"
                             data-ad-client="ca-pub-3129960251643439"
                             data-ad-slot="4184839470"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>
            </div>
            <div class="profile_content">
                @yield('content')
            </div>
        </div>
    </div>

    @include('includes.footer')

    @include('includes.cookies')

    <!-- Scripts -->
    @livewireScripts
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    {{-- Pusher js --}}
{{--    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>--}}
    <script>
        window.addEventListener('cart.removed', () => {
            console.log('removed');
            location.reload()
        })
    </script>
    @yield('js')
    @include('includes.fondy')
    @include('includes.noty-messages')
</body>
</html>
