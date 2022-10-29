<footer class="footer">
    <div class="container footer_wrapper">
        <div class="footer_brand">
            <a href="{{ route('home') }}" class="d-block footer_logo">
                @include('includes.svg', ['name' => 'logo'])
            </a>
            <a href="#" class="mobile_btn">
                @include('includes.svg', ['name' => 'google_play'])
            </a>
            <a href="#" class="mobile_btn">
                @include('includes.svg', ['name' => 'app_store'])
            </a>
        </div>
        <div class="footer_menu">
            <div class="footer_menu_col">
                <div class="footer_menu_item footer_menu_item__title">Меню</div>
                <a href="{{route('home')}}" class="footer_menu_item">Главная</a>
                <a href="{{route('lots.index')}}" class="footer_menu_item">Магазин</a>
                <a href="{{route('orders.index')}}" class="footer_menu_item">Фриланс</a>
                <a href="{{route('freelance.index')}}" class="footer_menu_item">Исполнители</a>
            </div>
            <div class="footer_menu_col">
                <div class="footer_menu_item footer_menu_item__title">Информация</div>
                <a href="{{route('help.company')}}" class="footer_menu_item">О компании</a>
                <a href="{{route('help.freelance')}}" class="footer_menu_item">О фрилансе</a>
                <a href="{{route('help.shop')}}" class="footer_menu_item">О магазине</a>
                <a href="{{route('help.policy')}}" class="footer_menu_item">Политика конфиденциальности</a>
                <a href="{{route('reviews')}}" class="footer_menu_item">Отзывы</a>
            </div>
            <div class="footer_menu_col">
                <div class="footer_menu_item footer_menu_item__title">Помощь</div>
                <a href="{{route('help.index', '#faq')}}" class="footer_menu_item">FAQ</a>
                <a href="{{route('help.index', '#price')}}" class="footer_menu_item">Прайс</a>
                <a href="{{route('help.conditions')}}" class="footer_menu_item">Условия пользования</a>
                <a href="{{route('help.sitemap')}}" class="footer_menu_item">Карта сайта</a>
            </div>
        </div>
        <div class="footer_right">
            <div class="footer_social">
                <a href="#" class="footer_social_item">
                    @include('includes.svg', ['name' => 'google_plus'])
                </a>
                <a href="#" class="footer_social_item">
                    @include('includes.svg', ['name' => 'facebook'])
                </a>
                <a href="#" class="footer_social_item">
                    @include('includes.svg', ['name' => 'vk'])
                </a>
                <a href="#" class="footer_social_item">
                    @include('includes.svg', ['name' => 'tg'])
                </a>
            </div>
            <button class="webz_btn open_modal" modal-wrapper="#feedback" id="feedback-btn">Обратная связь</button>
        </div>
    </div>
    <div class="footer_copyright">
        © 2021 - {{ \Carbon\Carbon::now()->year }} {{ config('app.url') }}
    </div>
</footer>

@include('includes.modals.feedback')
