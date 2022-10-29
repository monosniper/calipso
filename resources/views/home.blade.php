@extends('layouts.main')

@section('title')
    {{__('main.main')}}
@endsection

@section('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/selectize.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/home.css') }}"/>
    <style>
        .selectize-input {
            padding: 0 !important;
        }
        .selectize-input>input {
            font-size: 17px;
            width: auto;
        }
        .selectize-control.single  {
            z-index: 11;
        }

        .selectize-input .item {
            font-size: 17px;
        }

    </style>
@endsection

@section('js')
    <script src="{{ asset('assets/js/selectize.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
    <script>

        $(document).ready(function() {

            new Splide( '#reviews', {
                type   : 'loop',
                perPage: 2,
                perMove: 1,
                gap: '2em',
                breakpoints: {
                    1100: {
                        perPage: 1,
                    },
                }
            } ).mount();

            new Splide( '#freelancers', {
                type   : 'loop',
                perPage: 1,
                gap: '2em',
                fixedWidth: '290px',
                focus  : 'center',
            } ).mount();

            new Splide( '#lots', {
                type   : 'loop',
                perPage: 3,
                gap: '2em',
                fixedWidth: '290px',
                focus  : 'center',
            } ).mount();

        });

        let category_field = $('#category').selectize();

        let tags_field = $('#tags').selectize();

        $('.reset').click(() => {
            category_field[0].selectize.clear();
            selectize_control[0].selectize.clear();
        })

        // Slider
        $(function() {

            var exits = ['fadeOut', 'fadeOutDown', 'fadeOutUpBig', 'bounceOut', 'bounceOutDown',
                'hinge', 'bounceOutUp', 'bounceOutLeft', 'rotateOut', 'rotateOutUpLeft',
                'lightSpeedOut', 'rollOut'];

            var entrances = ['fadeIn', 'fadeInDown', 'fadeInRight', 'bounceIn', 'bounceInRight',
                'rotateIn', 'rotateInDownLeft', 'lightSpeedIn', 'rollIn', 'bounceInDown'];

            var slides = $('#slides'),
                ignoreClicks = false;

            $('.control').click(function(e){
                if(ignoreClicks){

                    // Если клики по стрелкам - игнорируются, события срабатывания остальных обработчиков
                    // останавливаются.
                    //Другими словами, во время анимации перехода от слайда к слайду мы запускаем игнорирования клика по стрелкам, что блокирует преждевременный переход к следующему слайду до завершения текущей анимации

                    e.stopImmediatePropagation();
                    return false;
                }

                // В другом случае позволяется кликать по стрелкам, но устанавливается флаг ignoreClicks. Т.е. возможность клика по стрелкам сохраняется но, преждевременного перехода к следующему слайду не происходит

                ignoreClicks = true;
            });

            // отслеживаем событие клика по стрелке "вперёд"
            $('.control.next').click(function(e){

                e.preventDefault();

                //верхний элемент
                var elem = $('#slides div:last');

                // применяем случайный выбор анимации
                elem.addClass('animate__animated')
                    .addClass('animate__bounceInRight');

                setTimeout(function(){

                    //сбрасываем классы
                    $('#slides .slide').each((i, slide) => $(slide).removeClass('active'));
                    elem.attr('class','slide active').prependTo(slides);

                    // анимация закончена!
                    // разрешить клики по стрелкам снова.
                    ignoreClicks = false;

                },500);
            });

            // отслеживаем событие клика по стрелке "назад"
            $('.control.previous').click(function(e){

                e.preventDefault();

                // самый нижний элемент
                var elem = $('#slides .slide:first');

                // передвинуть фотографию вверх и применить случайную анимацию

                elem.appendTo(slides)
                    .addClass('animate__animated')
                    .addClass('animate__bounceInLeft');

                setTimeout(function(){

                    // удалить классы
                    $('#slides .slide').each((i, slide) => $(slide).removeClass('active'));
                    elem.attr('class', 'slide active');

                    // анимация закончена!
                    // разрешить клики по стрелкам снова.
                    ignoreClicks = false;

                },1000);
            });

            // начать авто-слайдшоу
            // var slideshow = setInterval(function(){

            //     // Симулировать клик по стрелке каждые 1.5 секунд
            //     $('.control.next').trigger('click',[true]);

            // }, 1500);

        });
    </script>
@endsection

@section('header')
    @include('includes.header')
@endsection

@section('content')

    @include('includes.banner')

    <div class="container">
        <form action="{{route('lots.index')}}" method="get" class="search_form">
            <div class="search_wrapper">
                <input type="text" class="search_field" name="q" id="query" placeholder="{{__('main.search')}}">
                <select class="search_field" name="category" id="category">
                    <option value="">{{__('main.category')}}</option>
                    @foreach($categories as $category)
                        <option value="{{$category->slug}}">{{$category->name}}</option>
                    @endforeach
                </select>
                <input type="number" placeholder="{{__('main.filters.from')}}: " name="price_from" id="price_from" class="search_field">
                <input type="number" placeholder="{{__('main.filters.to')}}: " name="price_to" id="price_to" class="search_field">
                <button class="webz_btn webz_btn_light" id="reset" type="reset">{{__('main.reset')}}</button>
                <select class="search_field" name="tags[]" multiple id="tags">
                    <option value="">{{__('main.tags')}}</option>
                    @foreach($tags as $tag)
                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                    @endforeach
                </select>
                <button class="webz_btn" type="submit" id="filter">{{__('main.filter')}}</button>
                {{-- <div class="search_top search_row">
                    <input type="text" class="search_field" name="query" id="query" placeholder="Поиск">
                </div>
                <div class="search_center search_row">
                    <select name="category" id="category" placeholder="Категория">
                        <option value="">Категория</option>
                        <option value="test">Category 1</option>
                        <option value="testw">Category 2</option>
                        <option value="test3">Category 3</option>
                    </select>
                    <div class="sm-from">
                        <button class="webz_btn webz_btn_light reset" type="reset">Сброс</button>
                    </div>

                </div>
                <div class="search_bottom search_row">
                    <input type="text" placeholder="От: " name="price['from']" id="price_from" class="search_field">
                    <input type="text" placeholder="До: " name="price['from']" id="price_to" class="search_field">
                    <div class="xs-only">
                        <button class="webz_btn webz_btn_light reset" type="reset">Сброс</button>
                    </div>
                    <select name="tags[]" multiple class="tags">
                        <option value="test1">Tag 1</option>
                        <option value="test2">Tag 2</option>
                        <option value="test3">Tag 3</option>
                    </select>
                    <button class="webz_btn" type="submit">Фильтр</button>
                </div> --}}
            </div>
        </form>
    </div>

    <section class="section section_about section_right_circle">
        <div class="container section_wrapper">
            <div class="section_text">
                <h2 class="section_title webz_underline_half">{{__('main.about')}}</h2>
                <p class="section_par">Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore error
                    voluptatum, veniam enim magnam aut impedit. Impedit, corrupti. Rerum impedit pariatur saepe
                    asperiores possimus rem hic, nulla ullam accusamus ratione.</p>
                <p class="section_par">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum provident repellat
                    unde dignissimos ipsam explicabo voluptates consequatur illum fuga, rem est molestias, odio facilis
                    esse laboriosam ad! Enim, velit sit.Autem fugit modi aliquid perspiciatis debitis sit perferendis
                    esse! Tempora nisi ipsam magni soluta, possimus quibusdam reprehenderit ad alias quis facilis
                    doloribus labore saepe tenetur amet laboriosam, culpa voluptatibus ipsum?</p>
                <div class="section_details">
                    <a href="{{route('about')}}" class="section_details_link">
                        {{__('main.details')}}
                        @include('includes.svg', ['name' => 'arrow_right'])
                    </a>
                </div>
            </div>
            <div class="section_img">
                <img src="{{ asset('assets/img/section_about.png') }}" class="full-height" alt="About">
            </div>
        </div>
    </section>

    <section class="section_plus">
        <div class="container section_plus_wrapper">
            <div class="plus_card plus_card_noback">
                <h2 class="section_title">{{__('home.our_benefits')}}</h2>
            </div>
            <div class="plus_card">
                <div class="plus_card_icon">
                    @include('includes.svg', ['name' => 'plus_1'])
                </div>
                <div class="plus_card_text">
                    {{__('home.benefits.1')}}
                </div>
            </div>
            <div class="plus_card">
                <div class="plus_card_icon">
                    @include('includes.svg', ['name' => 'plus_2'])
                </div>
                <div class="plus_card_text">
                    {{__('home.benefits.2')}}
                </div>
            </div>
            <div class="plus_card">
                <div class="plus_card_icon">
                    @include('includes.svg', ['name' => 'plus_3'])
                </div>
                <div class="plus_card_text">
                    {{__('home.benefits.3')}}
                </div>
            </div>
            <div class="plus_card">
                <div class="plus_card_icon">
                    @include('includes.svg', ['name' => 'plus_4'])
                </div>
                <div class="plus_card_text">
                    {{__('home.benefits.4')}}
                </div>
            </div>
            <div class="plus_card">
                <div class="plus_card_icon">
                    @include('includes.svg', ['name' => 'plus_5'])
                </div>
                <div class="plus_card_text">
                    {{__('home.benefits.5')}}
                </div>
            </div>
        </div>
    </section>

    <section class="section section_shop section_left_circle">
        <div class="container section_wrapper">
            <div class="section_img">
                <img src="{{ asset('assets/img/section_shop.png') }}" class="full-height" alt="Shop">
            </div>
            <div class="section_text">
                <h2 class="section_title webz_underline_half">{{__('home.about_shop')}}</h2>
                <p class="section_par">Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore error
                    voluptatum, veniam enim magnam aut impedit. Impedit, corrupti. Rerum impedit pariatur saepe
                    asperiores possimus rem hic, nulla ullam accusamus ratione.</p>
                <p class="section_par">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum provident repellat
                    unde dignissimos ipsam explicabo voluptates consequatur illum fuga, rem est molestias, odio facilis
                    esse laboriosam ad! Enim, velit sit.Autem fugit modi aliquid perspiciatis debitis sit perferendis
                    esse! Tempora nisi ipsam magni soluta, possimus quibusdam reprehenderit ad alias quis facilis
                    doloribus labore saepe tenetur amet laboriosam, culpa voluptatibus ipsum?</p>
                <div class="section_details">
                    <a href="{{route('about', '#shop')}}" class="section_details_link">
                        {{__('main.details')}}
                        @include('includes.svg', ['name' => 'arrow_right'])
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="section section_reviews section_grey">
{{--        <div class="xs-only">--}}
{{--            <div class="container">--}}
{{--                <h2 class="section_title">{{__('main.reviews')}}</h2>--}}
{{--            </div>--}}
{{--            <div class="splide" id="reviews">--}}
{{--                <div class="splide__track">--}}
{{--                    <ul class="splide__list">--}}
{{--                        <li class="splide__slide">--}}
{{--                            <div class="review">--}}
{{--                                <div class="review_img">--}}
{{--                                    <img src="{{ asset('assets/img/review_1.png') }}" alt="Review">--}}
{{--                                </div>--}}
{{--                                <div class="review_author">Имя Ф.</div>--}}
{{--                                <div class="review_text">Lorem Ipsum is simply dummy text of the printing</div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="splide__slide">--}}
{{--                            <div class="review">--}}
{{--                                <div class="review_img">--}}
{{--                                    <img src="{{ asset('assets/img/review_2.png') }}" alt="Review">--}}
{{--                                </div>--}}
{{--                                <div class="review_author">Имя Ф.</div>--}}
{{--                                <div class="review_text">Lorem Ipsum is simply dummy text of the printing</div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="splide__slide">--}}
{{--                            <div class="review">--}}
{{--                                <div class="review_img">--}}
{{--                                    <img src="{{ asset('assets/img/review_3.png') }}" alt="Review">--}}
{{--                                </div>--}}
{{--                                <div class="review_author">Имя Ф.</div>--}}
{{--                                <div class="review_text">Lorem Ipsum is simply dummy text of the printing</div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="splide__slide">--}}
{{--                            <div class="review">--}}
{{--                                <div class="review_img">--}}
{{--                                    <img src="{{ asset('assets/img/review_4.png') }}" alt="Review">--}}
{{--                                </div>--}}
{{--                                <div class="review_author">Имя Ф.</div>--}}
{{--                                <div class="review_text">Lorem Ipsum is simply dummy text of the printing</div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="sm-from">--}}
{{--            <div class="container">--}}
{{--                <h2 class="section_title">{{__('main.reviews')}}</h2>--}}
{{--                <div class="section_reviews_wrapper">--}}
{{--                    <div class="review">--}}
{{--                        <div class="review_img">--}}
{{--                            <img src="{{ asset('assets/img/review_1.png') }}" alt="Review">--}}
{{--                        </div>--}}
{{--                        <div class="review_author">Имя Ф.</div>--}}
{{--                        <div class="review_text">Lorem Ipsum is simply dummy text of the printing</div>--}}
{{--                    </div>--}}
{{--                    <div class="review">--}}
{{--                        <div class="review_img">--}}
{{--                            <img src="{{ asset('assets/img/review_2.png') }}" alt="Review">--}}
{{--                        </div>--}}
{{--                        <div class="review_author">Имя Ф.</div>--}}
{{--                        <div class="review_text">Lorem Ipsum is simply dummy text of the printing</div>--}}
{{--                    </div>--}}
{{--                    <div class="review">--}}
{{--                        <div class="review_img">--}}
{{--                            <img src="{{ asset('assets/img/review_3.png') }}" alt="Review">--}}
{{--                        </div>--}}
{{--                        <div class="review_author">Имя Ф.</div>--}}
{{--                        <div class="review_text">Lorem Ipsum is simply dummy text of the printing</div>--}}
{{--                    </div>--}}
{{--                    <div class="review">--}}
{{--                        <div class="review_img">--}}
{{--                            <img src="{{ asset('assets/img/review_4.png') }}" alt="Review">--}}
{{--                        </div>--}}
{{--                        <div class="review_author">Имя Ф.</div>--}}
{{--                        <div class="review_text">Lorem Ipsum is simply dummy text of the printing</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="section_details">--}}
{{--                    <a href="#" class="section_details_link">{{__('main.show_all_reviews')}}</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="container">
            <h2 class="section_title">{{__('main.reviews')}}</h2>
            <div class="splide" id="reviews">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <div class="review">
                                <div class="review_header">
                                    <div class="review_img">
                                        <img src="{{ asset('assets/img/review_1.png') }}" alt="Анастасия Благочева">
                                    </div>
                                    <div class="review_name">Анастасия Благочева</div>
                                </div>
                                <div class="review_body">
                                    Я одна из первых начала свою работу на Calipso, заработала за месяц больше 1500$, в то время как раньше за свою работу я получала в офисе всего около 500$
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="review">
                                <div class="review_header">
                                    <div class="review_img">
                                        <img src="{{ asset('assets/img/review_2.png') }}" alt="Илья Король">
                                    </div>
                                    <div class="review_name">Илья Король</div>
                                </div>
                                <div class="review_body">
                                    Я рисую с детства, люблю показывать свои картины и общаться с людьми за искуство, воспринимал это как хобби, но как-то наткнулся на Calipso и моё хобби стало приносить мне неплохие цифры на моем банковском счете. При этом я нашел людей, которые разделяют мои взгляды
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="review">
                                <div class="review_header">
                                    <div class="review_img">
                                        <img src="{{ asset('assets/img/review_3.png') }}" alt="Денис Повещенко">
                                    </div>
                                    <div class="review_name">Денис Повещенко</div>
                                </div>
                                <div class="review_body">
                                    Я часто бываю занят, занимаюсь профессиональной съемкой, рекламными роликами и тд, очень много времени занимает монотонная работа, поэтому часто передаю ее в руки другим.
                                    Ни разу не пожалел, все вовремя и качественно, спасибо!
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </section>

    <section class="section section_grey section_info">
        <div class="container section_info_wrapper">
            <div class="info-block">
                <h1>
                    @include('includes.svg', ['name' => 'logo'])
                </h1>
                <small class="sm">{{__('home.info.title')}}</small>
            </div>
            <div class="info-block">
                <h1>500000</h1>
                <small>{{__('home.info.active_orders')}}</small>
            </div>
            <div class="info-block">
                <h1>9000</h1>
                <small>{{__('home.info.new_orders_a_week')}}</small>
            </div>
            <div class="info-block">
                <h1>1000</h1>
                <small>{{__('home.info.new_orders_a_day')}}</small>
            </div>
        </div>
    </section>

    <section class="section section_freelancers section_grey">
        <div class="xs-only">
            <div class="container">
                <div class="section_header">
                    <h2 class="section_title">{{__('home.top_freelancers')}}</h2>
                    <div class="section_details">
                        <a href="#" class="section_details_link">{{__('home.all_freelancers')}}</a>
                    </div>
                </div>
            </div>
            <div class="splide" id="freelancers">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($top_freelancers as $freelancer)
                            <li class="splide__slide">
                                <div class="freelancer">
                                    <a href="{{route('freelance.freelancer', $freelancer->id)}}" class="freelancer_img">
                                        <img src="{{ $freelancer->getAvatar() }}" alt="{{$freelancer->fullName}}">
                                    </a>
                                    <a href="{{route('freelance.freelancer', $freelancer->id)}}" class="freelancer_author">{{$freelancer->fullName}}</a>
                                    <div class="freelancer_text">
                                        <div class="freelancer-rating">
                                            <i class="fas fa-star"></i>
                                            {{$freelancer->rating}}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="sm-from">
            <div class="container">
                <div class="section_header">
                    <h2 class="section_title">{{__('home.top_freelancers')}}</h2>
                    <div class="section_details">
                        <a href="{{route('freelance.index')}}" class="section_details_link">{{__('home.all_freelancers')}}</a>
                    </div>
                </div>
                <div class="section_freelancers_wrapper">
                    @foreach($top_freelancers as $freelancer)
                        <div class="freelancer">
                            <a href="{{route('freelance.freelancer', $freelancer->id)}}" class="freelancer_img">
                                <img src="{{ $freelancer->getAvatar() }}" alt="{{$freelancer->fullName}}">
                            </a>
                            <a href="{{route('freelance.freelancer', $freelancer->id)}}" class="freelancer_author">{{$freelancer->fullName}}</a>
                            <div class="freelancer_text">
                                <div class="freelancer-rating">
                                    <i class="fas fa-star"></i>
                                    {{$freelancer->rating}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </section>

    <div class="container" id="slider">
        <div class="slider" style="background-image: url('{{ asset('assets/img/slider.png') }}')">

            <div class="logo">
                @include('includes.svg', ['name' => 'logo'])
            </div>

            <div class="indicator">
                <div class="indicator_dot active"></div>
                <div class="indicator_dot"></div>
                <div class="indicator_dot"></div>
                <div class="indicator_dot"></div>
            </div>

            <div class="controls">
                <div class="control previous">
                    @include('includes.svg', ['name' => 'left'])
                </div>
                <div class="control next">
                    @include('includes.svg', ['name' => 'right'])
                </div>
            </div>

            <div class="slides" id="slides">
                <div class="slide active"></div>
                <div class="slide"></div>
                <div class="slide"></div>
                <div class="slide"></div>
            </div>
        </div>
    </div>

    <section class="section section_lots">
        <div class="md-only">
            <div class="container">
                <h2 class="section_title">{{__('home.premium_lots')}}</h2>
            </div>
            <div class="splide" id="lots">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($premium_lots as $premium_lot)
                            <li class="splide__slide">
                                @include('includes.lot', ['lot' => $premium_lot])
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="lg-only">
            <div class="container">
                <div class="section_header">
                    <h2 class="section_title">{{__('home.premium_lots')}}</h2>
                    <div class="section_details">
                        <a href="{{route('lots.index')}}" class="section_details_link">{{__('home.all_lots')}}</a>
                    </div>
                </div>
                <div class="section_lots_wrapper">
                    @foreach($premium_lots as $premium_lot)
                        @include('includes.lot', ['lot' => $premium_lot])
                    @endforeach
                </div>
            </div>
        </div>

        <div class="section section_grey premium_lots_grey_block"></div>

    </section>

    <section class="section section_dark section_mobile">
        <div class="container section_wrapper">
            <div class="section_text">
                <h2 class="section_title webz_underline_half">{{__('home.mobile_app')}}</h2>
                <p class="section_par">{{__('home.mobile.1')}}</p>
                <p class="section_par">{{__('home.mobile.2')}} </p>
                <p class="section_par">{{__('home.mobile.3')}}</p>
                <div class="section_details">
                    <a href="#" class="mobile_btn">
                        @include('includes.svg', ['name' => 'google_play', 'class' => 'full-height'])
                    </a>
                    <a href="#" class="mobile_btn">
                        @include('includes.svg', ['name' => 'app_store'])
                    </a>
                </div>
            </div>
            <div class="section_img">
                <img src="{{ asset('assets/img/section_mobile.png') }}" alt="Mobile">
            </div>
        </div>
    </section>

@endsection
