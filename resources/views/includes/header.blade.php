@php
    $locales = ['ru', 'en'];
@endphp

<header class="header {{ isset($dark) ? $dark ? 'header_dark' : '' : 'scroll' }}">
    @unless(auth()->check())
        @include('includes.modals.sign')
    @endunless

    <div class="lg-only">
        <div class="container header_wrapper">
            <div class="header_left">
                <a href="{{ route('home') }}" class="header_logo">
                    @include('includes.svg', ['name' => 'logo'])
                </a>
            </div>

            <div class="header_center">
                <div class="header_menu">
                    <a href="{{ route('home') }}" class="header_menu_item {{ Route::is('home') ? 'active' : '' }}">{{__('main.main')}}</a>
                    <a href="{{ route('lots.index') }}" class="header_menu_item {{ Route::is('lots.index') ? 'active' : '' }}">{{__('main.shop')}}</a>
                    <a href="{{ route('orders.index') }}" class="header_menu_item {{ Route::is('orders.index') ? 'active' : '' }}">{{__('main.freelance')}}</a>
                    <a href="{{ route('freelance.index') }}" class="header_menu_item {{ Route::is('freelance.index') ? 'active' : '' }}">{{__('main.freelancers')}}</a>
{{--                    <a href="{{ route('projects') }}" class="header_menu_item {{ Route::is('projects') ? 'active' : '' }}">{{__('main.projects')}}</a>--}}
                    <a href="{{ route('about') }}" class="header_menu_item {{ Route::is('about') ? 'active' : '' }}">{{__('main.about')}}</a>
                </div>
                <div class="header_details">
                    <div class="dropdown">
                        @foreach($locales as $locale)
                            @if($locale === request()->cookie('locale', app()->getLocale()))
                                <div class="header_language dropdown_toggler">
                                    {{strtoupper($locale)}}
                                    @include('includes.svg', ['name' => 'down'])
                                </div>
                            @else
                                <div class="header_language_dropdown_menu dropdown_menu">
                                    <a href="{{ route('locale', $locale) }}"
                                       class="header_language_dropdown_menu_item dropdown_menu_item">{{strtoupper($locale)}}</a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="header_item">
                        @livewire('cart-component', ['icon' => "shopping-cart", 'instance' => 'basket'])
                    </div>
                    <div class="header_item">
                        @livewire('wishlist')
                    </div>
                </div>
            </div>
            <div class="header_right">
                @auth
                    <div class="dropdown">
                        <div class="header_balance dropdown_toggler">${{auth()->user()->balanceFloat}}</div>
                        <div class="dropdown_menu">
                            <a class="dropdown_menu_item replenish-link">{{__('main.replenish')}}</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <div class="header_avatar dropdown_toggler"
                             style="background-image: url('{{ auth()->user()->getAvatar() }}')"></div>
                        <div class="header_avatar_dropdown_menu dropdown_menu">
                            <a href="{{ route('dashboard.cabinet') }}"
                               class="header_avatar_dropdown_menu_item dropdown_menu_item">{{__('main.profile')}}</a>
                            <a href="{{ route('logout') }}" class="header_avatar_dropdown_menu_item dropdown_menu_item">{{__('main.logout')}}</a>
                        </div>
                    </div>
                @else
                    <button class="webz_btn sign_btn">{{__('modals.sign.login')}}</button>
                @endauth
            </div>
        </div>
    </div>
    <div class="md-only">
        <div class="container header_wrapper">
            <div class="header_left">
                <a href="{{ route('home') }}" class="header_logo">
                    @include('includes.svg', ['name' => 'logo'])
                </a>
            </div>
            <div class="header_right">
                @auth
                    <div class="dropdown">
                        <div class="header_balance dropdown_toggler">${{auth()->user()->balanceFloat}}</div>
                        <div class="dropdown_menu">
                            <a class="dropdown_menu_item replenish-link">{{__('main.replenish')}}</a>
                        </div>
                    </div>
                @endauth
                <button class="header_collapse">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                        <g stroke-width="6.5" stroke-linecap="round">
                            <path
                                d="M72 82.286h28.75"
                                fill="#009100"
                                fill-rule="evenodd"
                                stroke="#fff"
                            />
                            <path
                                d="M100.75 103.714l72.482-.143c.043 39.398-32.284 71.434-72.16 71.434-39.878 0-72.204-32.036-72.204-71.554"
                                fill="none"
                                stroke="#fff"
                            />
                            <path
                                d="M72 125.143h28.75"
                                fill="#009100"
                                fill-rule="evenodd"
                                stroke="#fff"
                            />
                            <path
                                d="M100.75 103.714l-71.908-.143c.026-39.638 32.352-71.674 72.23-71.674 39.876 0 72.203 32.036 72.203 71.554"
                                fill="none"
                                stroke="#fff"
                            />
                            <path
                                d="M100.75 82.286h28.75"
                                fill="#009100"
                                fill-rule="evenodd"
                                stroke="#fff"
                            />
                            <path
                                d="M100.75 125.143h28.75"
                                fill="#009100"
                                fill-rule="evenodd"
                                stroke="#fff"
                            />
                        </g>
                    </svg>
                </button>
            </div>
        </div>
        <div class="container">
            <div class="header_collapse_content">
                <div class="header_center">
                    <div class="header_menu">
                        <a href="{{ route('home') }}" class="header_menu_item {{ Route::is('home') ? 'active' : '' }}">{{__('main.main')}}</a>
                        <a href="{{ route('lots.index') }}" class="header_menu_item {{ Route::is('lots.index') ? 'active' : '' }}">{{__('main.shop')}}</a>
                        <a href="{{ route('orders.index') }}" class="header_menu_item {{ Route::is('orders.index') ? 'active' : '' }}">{{__('main.freelance')}}</a>
                        <a href="{{ route('freelance.index') }}" class="header_menu_item {{ Route::is('freelance.index') ? 'active' : '' }}">{{__('main.freelancers')}}</a>
{{--                        <a href="{{ route('projects') }}" class="header_menu_item {{ Route::is('projects') ? 'active' : '' }}">{{__('main.projects')}}</a>--}}
                        <a href="{{ route('about') }}" class="header_menu_item {{ Route::is('about') ? 'active' : '' }}">{{__('main.about')}}</a>
                    </div>
                    <div class="header_details">
                        <div class="dropdown">
                            @foreach($locales as $locale)
                                @if($locale === request()->cookie('locale', app()->getLocale()))
                                    <div class="header_language dropdown_toggler">
                                        {{strtoupper($locale)}}
                                        @include('includes.svg', ['name' => 'down'])
                                    </div>
                                @else
                                    <div class="header_language_dropdown_menu dropdown_menu">
                                        <a href="{{ route('locale', $locale) }}"
                                           class="header_language_dropdown_menu_item dropdown_menu_item">{{strtoupper($locale)}}</a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="header_item">
                            @livewire('cart-component', ['icon' => "shopping-cart", 'instance' => 'basket'])
                        </div>
                        <div class="header_item">
                            @livewire('wishlist')
                        </div>
                    </div>
                </div>
                <div class="header_right">
                    @auth
                        <div class="dropdown">
                            <div class="header_avatar dropdown_toggler"
                                 style="background-image: url({{ auth()->user()->getAvatar() }})"></div>
                            <div class="header_avatar_dropdown_menu dropdown_menu">
                                <a href="{{ route('dashboard.cabinet') }}"
                                   class="header_avatar_dropdown_menu_item dropdown_menu_item">{{__('main.profile')}}</a>
                                <a href="{{ route('logout') }}"
                                   class="header_avatar_dropdown_menu_item dropdown_menu_item">{{__('main.logout')}}</a>
                            </div>
                        </div>
                    @else
                        <button class="webz_btn sign_btn">{{__('modals.sign.login')}}</button>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

@isset($normalize)
    @if ($normalize)
        <div class="header_normalize"></div>
    @endif
@endisset
