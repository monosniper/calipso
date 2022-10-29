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

    <title>@yield('title') - {{__('main.profile')}} | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('assets/css/simple-grid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />

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
                'route' => 'cabinet',
                'icon' => 'user',
                'name' => __('main.profile'),
            ],
            [
                'route' => 'change-password',
                'icon' => 'lock',
                'name' => __('main.change_password'),
            ],
            [
                'route' => 'archive',
                'icon' => 'archive',
                'name' => __('main.archive'),
                'count' => auth()->user()->purchasedLots()->count(),
            ],
            [
                'route' => 'basket',
                'icon' => 'basket_outline',
                'name' => __('main.basket'),
                'counter' => 'basket-count'
            ],
            [
                'route' => 'wishlist',
                'icon' => 'heart_outline',
                'name' => __('main.wishlist'),
                'counter' => 'wishlist-count'
            ],
            [
                'route' => 'lots',
                'icon' => 'lots',
                'name' => __('main.my_lots'),
                'count' => $user->lots_count,
            ],
            [
                'route' => 'orders',
                'icon' => 'order',
                'name' => __('main.my_orders'),
                'count' => $user->orders_count,
            ],
            [
                'route' => 'work',
                'icon' => 'briefcase',
                'name' => __('main.work'),
                'count' => $user->orders_work_count,
            ],
            // [
            //    'route' => 'cabinet',
            //    'icon' => 'user',
            //    'name' => 'Найти исполнителя',
            //],
            [
                'route' => 'pay-history',
                'icon' => 'history',
                'name' => __('main.pay_history'),
            ],
            [
                'route' => 'withdraw',
                'icon' => 'withdraw',
                'name' => __('main.withdraw'),
            ],
            [
                'route' => 'freelancer',
                'icon' => 'freelancer',
                'name' => __('main.become_freelancer'),
            ],
        ];
    @endphp

    <div class="container">
        <div class="profile_wrapper">
            <div class="profile_sidebar">
                <div class="grey_block grey_block_full grey_block_min_padding">
                    <div class="profile_menu">
                        @foreach ($menu_items as $item)
                            <a href="{{route('dashboard.'.$item['route'])}}" class="profile_menu_item {{url()->current() === route('dashboard.'.$item['route']) ? 'active' : ''}}">
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
        <script src="https://api-maps.yandex.ru/2.1/?apikey=96e40232-a3b1-4f7d-9fca-b0e0233d4364&lang=ru_RU" type="text/javascript">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
        {{-- Pusher js --}}
{{--        <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>--}}

        @livewireScripts
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
        <script>
            window.addEventListener('cart.removed', () => {
                console.log('removed');
                location.reload()
            })
        </script>
    @yield('js')
        @include('includes.noty-messages')
        @include('includes.fondy')
</body>
</html>
