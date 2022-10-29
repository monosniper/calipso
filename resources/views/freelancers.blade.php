@extends('layouts.main')

@section('title')
{{__('main.freelancers')}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/freelancers.css') }}"/>
@endsection

@section('js')
    <script>
        const toggler = document.querySelector('.filter-toggler');
        const panel = document.querySelector('.filter-panel');

        toggler.addEventListener('click', () => {
            toggler.classList.toggle('active');
            panel.classList.toggle('active');
        });

        ymaps.ready(init);

        function init() {
            // Создаем выпадающую панель с поисковыми подсказками и прикрепляем ее к HTML-элементу по его id.
            var suggestView1 = new ymaps.SuggestView('location');
        }
    </script>
@endsection

@section('content')

    <div class="container main">
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                @include('includes.breadcrumbs', [
                    'items' => [
                        [
                            'link' => route('home'),
                            'title' => __('main.main'),
                        ],
                         [
                            'link' => route('freelance.index'),
                            'title' => __('main.freelancers'),
                        ],
                    ]
                ])
            </div>
            <div class="col-lg-4 col-sm-12">
                @include('includes.search', ['page' => 'freelance.index'])
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-12">
                <div class="white_block white_block_no_x_padding sidebar">
                    <div class="sidebar-title">{{__('main.filters.freelance_category')}}</div>
                    <div class="sidebar-items">
                        @foreach($categories as $category)
                            <a href="{{ route('freelance.index', ['category' => $category->slug] + request()->except(['page']))  }}" class="sidebar-item {{ request('category') === $category->slug ? 'active' : ''  }}">
                                <div class="sidebar-item-name">{{$category->name}}</div>
                                <div class="sidebar-item-count">{{$category->freelancers_count}}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="white_block white_block_no_x_padding sidebar">
                    <div class="sidebar-title">{{__('main.filters.location')}}</div>
                    <form action="{{route('freelance.index')}}" class="sidebar-form">
                        <input id="location" type="text" placeholder="{{__('main.search')}}:" value="{{request('location')}}" name="location" class="sidebar-field">
                        @include('includes.form-request-fields', ['except' => ['page', 'location']])
                        <button class="webz_btn sidebar_btn">{{__('main.ready')}}</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-9 col-sm-12">
                <div class="white_block filter-block">
                    <div class="filter-header">
                        <span>{{__('main.freelancers_have')}}: {{$freelancers->total()}}</span>
                        <span class="filter-toggler">{{__('main.additional')}} <i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="filter-panel {{request()->has('filters') ? 'active' : ''}}">
                            @php
                                $request_filters = explode(',', request()->filters);

                                $filters = [
                                    [
                                        'title' => __('main.filters.has_reviews'),
                                        'filter' => 'reviews',
                                    ],
                                    [
                                        'title' => __('main.filters.no_negative_reviews'),
                                        'filter' => 'nice_reviews',
                                    ],
                                ];
                            @endphp
                            @foreach($filters as $filter)
                                @php
                                    $has_filter = in_array($filter['filter'], $request_filters);
                                    $filters = $has_filter ? \Illuminate\Support\Arr::except($request_filters, array_search($filter['filter'], $request_filters)) : [...$request_filters, $filter['filter']];
                                @endphp
                                <a href="{{ route('freelance.index', ['filters' => implode(',', $filters)] + request()->except(['page']))  }}" class="filter-panel-item {{ $has_filter  ? 'active' : ''  }}">
                                    <div class="filter-panel-item-name">{{$filter['title']}}</div>
                                </a>
                            @endforeach
                    </div>
                </div>
                <div class="freelancers">
                    @forelse ($freelancers as $freelancer)
                        @include('includes.freelancer', ['freelancer' => $freelancer])
                    @empty
                        <p>
                            {{__('main.nothing_found')}}
                        </p>
                    @endforelse
                </div>
                {!! $freelancers->appends(request()->except('page'))->render() !!}
            </div>
        </div>
    </div>

@endsection
