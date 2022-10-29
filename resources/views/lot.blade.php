@extends('layouts.main')

@section('title')
{{$lot->title}}
@endsection

@section('css')
    <link
        rel="stylesheet"
        href="https://unpkg.com/swiper@7/swiper-bundle.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css" integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/rateit.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/pages/lot.css') }}"/>
@endsection

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script src="{{asset('assets/js/jquery.rateit.min.js')}}"></script>
    <script>
        const swiper = new Swiper(".thumbsSwiper", {
            spaceBetween: 20,
            slidesPerView: 3,
            navigation: {
                nextEl: ".next",
                prevEl: ".prev",
            },
            breakpoints: {
                720: {
                    direction: 'vertical',
                },
            }
        });
        const swiper2 = new Swiper(".lotSwiper", {
            centeredSlides: true,
            centeredSlidesBounds: true,
            spaceBetween: 10,
            navigation: {
                nextEl: ".next",
                prevEl: ".prev",
            },
            thumbs: {
                swiper: swiper,
            },
        });

        new Splide( '.premium-lots', {
            type   : 'loop',
            perPage: 3,
            gap: '2em',
            fixedWidth: '365px',
            fixedHeight: '450px',
            focus  : 'center',
        } ).mount();

        if($('.same-lots').length) {
            new Splide( '.same-lots', {
                type   : 'loop',
                perPage: 3,
                gap: '2em',
                fixedWidth: '365px',
                fixedHeight: '450px',
                focus  : 'center',
            } ).mount();
        }

        $('.lotSwiper .swiper-slide')
            // tile mouse actions
            .on('mouseover', function(){
                $(this).children('.photo').css({'transform': 'scale(1.5)'});
            })
            .on('mouseout', function(){
                $(this).children('.photo').css({'transform': 'scale(1)'});
            })
            .on('mousemove', function(e){
                $(this).children('.photo').css({'transform-origin': ((e.pageX - $(this).offset().left) / $(this).width()) * 100 + '% ' + ((e.pageY - $(this).offset().top) / $(this).height()) * 100 +'%'});
            })
            // tiles set up
            .each(function(){
                $(this)
                    // add a photo container
                    .append('<div class="photo"></div>')
                    // set up a background image for each tile based on data-image attribute
                    .children('.photo').css({'background-image': 'url('+ $(this).attr('data-image') +')'});
            })

        if($('.show-description').length) {
            $('.show-description').click(() => {
                $('.hidden-description').toggle(200);
            })
        }
        if($('.show-properties').length) {
            $('.show-properties').click(() => {
                $('.single-lot-property.hidden').toggle(200);
            })
        }
    </script>
@endsection

@section('content')
    <div class="container main">
        <div class="row">
            <div class="col-12">
                @include('includes.breadcrumbs', ['items' => $breadcrumbs])
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2 class="single-lot-title">{{ $lot->title  }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="single-lot-details">
                    @if($lot->isPremium)
                        <div class="single-lot-term premium">{{__('main.premium')}}</div>
                    @endif
                    @if($lot->isTop())
                        <div class="single-lot-term top">{{__('main.top')}}</div>
                    @endif
                    <div class="single-lot-rating">
                        @include('includes.rating', ['rating' => (int)$lot->avg_rating])
                    </div>
                    <a href="#reviews" class="single-lot-reviews-count">
                        {{trans_choice('main.reviews_count', $lot->reviews_count, ['count' => $lot->reviews_count])}}
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-sm-12">
                <div class="row">
                    <div class="col-lg-2 col-sm-12">
                        <div class="prev">
                            @include('includes.svg', ['name' => "arrow-top"])
                        </div>
                        <div thumbsSlider="" class="swiper thumbsSwiper">
                            <div class="swiper-wrapper">
                                @foreach($lot->getMedia('images') as $image)
                                    <div class="swiper-slide">
                                        <img src="{{$image->getUrl()}}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="next">
                            @include('includes.svg', ['name' => "arrow-down"])
                        </div>
                    </div>
                    <div class="col-lg-10 col-sm-12">
                        <div
                            style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                            class="swiper lotSwiper"
                        >
                            <div class="swiper-wrapper">
                                @foreach($lot->getMedia('images') as $image)
                                    <div class="swiper-slide"  data-image="{{$image->getUrl()}}">
                                        <img src="{{$image->getUrl()}}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-sm-12">
                <div class="row">
                    <div class="col-12">
                        <div class="single-lot-tags">
                            @forelse($lot->tags as $tag)
                                <a href="{{route('lots.index', ['tags' => $tag->name])}}" class="single-lot-tag">#{{$tag->name}}</a>
                            @empty

                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="single-lot-header">
                            <div class="single-lot-price">
                                @if($lot->discount_price)
                                    <span>${{$lot->discount_price}}</span>
                                    <div class="single-lot-price-discount">
                                        <div class="single-lot-price-discount-top">${{$lot->price}}</div>
                                        <div class="single-lot-price-discount-down">-${{(int)$lot->price - (int)$lot->discount_price}}</div>
                                    </div>
                                @else
                                    <span>${{$lot->price}}</span>
                                @endif
                            </div>
                            <div class="single-lot-basket">
                                <span onclick="Livewire.emit('add', 'wishlist', '{{$lot->slug}}')" class="material-icons-outlined">favorite_border</span>
                                @auth
                                    @if(auth()->user()->hasPurchasedLot($lot->id) || auth()->id() === $lot->user_id)
                                        <a href="{{route('lots.get', $lot->slug)}}" class="webz_btn">{{__('main.get')}}</a>
                                    @else
                                        <button onclick="Livewire.emit('add', 'basket', '{{$lot->slug}}')" class="webz_btn">{{__('main.add_to_basket')}}</button>
                                    @endif
                                @else
                                    <button onclick="Livewire.emit('add', 'basket', '{{$lot->slug}}')" class="webz_btn">{{__('main.add_to_basket')}}</button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="single-lot-description">
                            {!! $lot->description_len > 200 ? $lot->getShortDescription() : $lot->description  !!}
                            <div class="hidden-description">{!! $lot->description !!}</div>
                            @if($lot->description_len > 200)
                                <div>
                                    <span class="show-description">{{__('main.show_full_description')}}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @if($lot->properties)
                            <table class="single-lot-properties">
                                @forelse($lot->properties as $prop)
                                    <tr class="single-lot-property {{ $loop->index < 5 ?: 'hidden' }}">
                                        <th class="single-lot-property-name">
                                        <span class="th-border">
                                            <span>{{ is_array($prop) ? $prop['key'] : $prop->key }}</span>
                                        </span>
                                        </th>
                                        <td class="single-lot-property-value">{{ is_array($prop) ? $prop['value'] : $prop->value }}</td>
                                    </tr>
                                @empty

                                @endforelse
                            </table>
                            @if(count($lot->properties) > 5)
                                <span class="show-properties">{{__('main.show_full_props')}}</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3 class="single-lot-title">{{__('home.premium_lots')}}</h3>
                <div class="splide premium-lots">
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
        </div>
        @if($same_lots->count())
            <div class="row">
                <div class="col-12">
                    <h3 class="single-lot-title">{{ __('main.other_lots_in', ['name' => $lot->category->name])}}</h3>
                    <div class="splide same-lots">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach($same_lots as $same_lot)
                                    <li class="splide__slide">
                                        @include('includes.lot', ['lot' => $same_lot])
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <livewire:reviews reviewable_type="{{\App\Models\Lot::class}}" reviewable_id="{{$lot->id}}" />
    </div>
    @include('includes.modals.review', ['reviewable_type' => \App\Models\Lot::class, 'reviewable_id' => $lot->id])
@endsection
