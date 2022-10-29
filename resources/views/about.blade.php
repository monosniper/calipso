@extends('layouts.main')

@section('title')
{{__('main.about')}}
@endsection

@section('content')

    <div class="container main">
        <h1 class="about-title">{{__('about.like')}}</h1>

        <div class="row">
            <div class="col-sm-6 col-xs-12 about-choice-tab active" data-tab="shop">
                @include('includes.svg', ['name' => 'shop_'.app()->getLocale()])
            </div>
            <div class="col-sm-6 col-xs-12 about-choice-tab" data-tab="freelance">
                @include('includes.svg', ['name' => 'freelance_'.app()->getLocale()])
            </div>
        </div>

        <div class="about-choice">
            <div data-tab="shop" class="about-choice-tab-content active">
                <div class="row about-row about-row-left wow fadeIn">
                    <div class="col-lg-5 col-sm-12 about-row-img">
                        @include('includes.svg', ['name' => 'shop_1'])
                    </div>
                    <div class="col-lg-7 col-sm-12 about-row-text">
                        <h3 class="about-row-title">{{__('about.titles.shop.1')}}</h3>
                        <p class="about-row-description">{{__('about.descriptions.shop.1')}}</p>
                    </div>
                </div>
                <div class="row about-row about-row-right wow fadeIn">
                    <div class="col-lg-7 col-sm-12 about-row-text">
                        <h3 class="about-row-title">{{__('about.titles.shop.1')}}</h3>
                        <p class="about-row-description">{{__('about.descriptions.shop.1')}}</p>
                    </div>
                    <div class="col-lg-5 col-sm-12 about-row-img">
                        @include('includes.svg', ['name' => 'shop_2'])
                    </div>
                </div>
                <div class="row about-row about-row-left wow fadeIn">
                    <div class="col-lg-5 col-sm-12 about-row-img">
                        @include('includes.svg', ['name' => 'shop_3'])
                    </div>
                    <div class="col-lg-7 col-sm-12 about-row-text">
                        <h3 class="about-row-title">{{__('about.titles.shop.1')}}</h3>
                        <p class="about-row-description">{{__('about.descriptions.shop.1')}}</p>
                    </div>
                </div>
            </div>
            <div data-tab="freelance" class="about-choice-tab-content">
                <div class="row about-row about-row-left wow fadeIn">
                    <div class="col-lg-5 col-sm-12 about-row-img">
                        @include('includes.svg', ['name' => 'freelance_1'])
                    </div>
                    <div class="col-lg-7 col-sm-12 about-row-text">
                        <h3 class="about-row-title">{{__('about.titles.freelance.1')}}</h3>
                        <p class="about-row-description">{{__('about.descriptions.freelance.1')}}</p>
                    </div>
                </div>
                <div class="row about-row about-row-right wow fadeIn">
                    <div class="col-lg-7 col-sm-12 about-row-text">
                        <h3 class="about-row-title">{{__('about.titles.freelance.1')}}</h3>
                        <p class="about-row-description">{{__('about.descriptions.freelance.1')}}</p>
                    </div>
                    <div class="col-lg-5 col-sm-12 about-row-img">
                        @include('includes.svg', ['name' => 'freelance_2'])
                    </div>
                </div>
                <div class="row about-row about-row-left wow fadeIn">
                    <div class="col-lg-5 col-sm-12 about-row-img">
                        @include('includes.svg', ['name' => 'freelance_3'])
                    </div>
                    <div class="col-lg-7 col-sm-12 about-row-text">
                        <h3 class="about-row-title">{{__('about.titles.freelance.1')}}</h3>
                        <p class="about-row-description">{{__('about.descriptions.freelance.1')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/about.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
@endsection

@section('js')
    <script src="{{asset('assets/js/wow.min.js')}}"></script>
    <script>
        new WOW().init();

        const animated_class = 'animate__animated';
        $('.about-choice-tab').each((i, tab) => {
            $(tab).click(() => {
                $('.about-choice-tab.active').removeClass('active');
                $(tab).addClass('active');
                $(`.about-choice-tab-content.active`).removeClass('active');
                $(`.about-choice-tab-content[data-tab=${$(tab).attr('data-tab')}]`).addClass('active');

                $('.about-row.'+animated_class).removeClass(animated_class);
                $('.about-row').addClass(animated_class);
            })
        })
    </script>
@endsection
