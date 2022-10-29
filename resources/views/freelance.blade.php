@extends('layouts.main')

@section('title')
{{__('main.freelance')}}
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap3.min.css" integrity="sha512-MNbWZRRuTPBahfBZBeihNr9vTJJnggW3yw+/wC3Ev1w6Z8ioesQYMS1MtlHgjSOEKBpIlx43GeyLM2QGSIzBDg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/freelance.css') }}"/>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        const toggler = document.querySelector('.filter-toggler');
        const panel = document.querySelector('.filter-panel');

        toggler.addEventListener('click', () => {
            toggler.classList.toggle('active');
            panel.classList.toggle('active');
        });

        $('#tags').selectize({delimiter: ','});
    </script>
@endsection

@section('content')

    <div class="container main">
        <div class="row">
            <div class="col-lg-7 col-sm-12">
                @include('includes.breadcrumbs', ['items' => $breadcrumbs])
            </div>
            <div class="col-lg-5 col-sm-12">
                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        @auth
                            <a href="{{route('orders.create')}}" class="webz_btn white">{{__('main.make_order')}}</a>
                        @endauth
                    </div>
                    <div class="col-lg-8 col-sm-12">
                        @include('includes.search', ['page' => 'orders.index'])
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-12">
                <div class="white_block white_block_no_x_padding sidebar">
                    <div class="sidebar-title">{{ count($breadcrumbs) > 2 ? $breadcrumbs[count($breadcrumbs)-1]['title'] : 'Сфера деятельности' }}</div>
                    <div class="sidebar-items">
                        @foreach($categories as $category)
                            <a href="{{ route('orders.index', ['category' => $category->slug] + request()->except(['page']))  }}" class="sidebar-item {{ request('category') === $category->slug ? 'active' : ''  }}">
                                <div class="sidebar-item-name">{{$category->name}}</div>
                                <div class="sidebar-item-count">{{$category->orders_count}}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="white_block white_block_no_x_padding sidebar">
                    <div class="sidebar-title">{{__('main.filters.price_project')}}</div>
                    <form action="{{route('orders.index')}}" class="sidebar-form">
                        <div class="sidebar-field-group">
                            <input type="number" placeholder="{{__('main.filters.from')}}:" value="{{request('price_from')}}" name="price_from" class="sidebar-field">
                            <input type="number" placeholder="{{__('main.filters.to')}}:" value="{{request('price_to')}}" name="price_to" class="sidebar-field">
                        </div>
                        @include('includes.form-request-fields', ['except' => ['page', 'price_from', 'price_to', 'tags']])
                        <button class="webz_btn sidebar_btn">{{__('main.ready')}}</button>
                    </form>
                </div>
                @include('includes.tags-filter', ['link' => route('lots.index')])
            </div>
            <div class="col-lg-9 col-sm-12">
                <div class="white_block filter-block">
                    <div class="filter-header">
                        <span>{{__('main.results')}}: {{$orders->total()}}</span>
                        <span class="filter-toggler">{{__('main.additional')}} <i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="filter-panel {{request()->has('filters') ? 'active' : ''}}">
                            @php
                                $request_filters = explode(',', request()->filters);

                                $filters = [
                                    [
                                        'title' => __('main.filters.only_urgent_orders'),
                                        'filter' => 'urgent',
                                    ],
                                    [
                                        'title' => __('main.filters.has_reviews'),
                                        'filter' => 'reviews',
                                    ],
                                    [
                                        'title' => __('main.filters.no_negative_reviews'),
                                        'filter' => 'nice_reviews',
                                    ],
                                    [
                                        'title' => __('main.filters.max_2_offers'),
                                        'filter' => 'min_offers',
                                    ],
                                ];
                            @endphp
                            @foreach($filters as $filter)
                                @php
                                    $has_filter = in_array($filter['filter'], $request_filters);
                                    $filters = $has_filter ? \Illuminate\Support\Arr::except($request_filters, array_search($filter['filter'], $request_filters)) : [...$request_filters, $filter['filter']];
                                @endphp
                                <a href="{{ route('orders.index', ['filters' => implode(',', $filters)] + request()->except(['page']))  }}" class="filter-panel-item {{ $has_filter  ? 'active' : ''  }}">
                                    <div class="filter-panel-item-name">{{$filter['title']}}</div>
                                </a>
                            @endforeach
                    </div>
                </div>
                <div class="orders">
                    @forelse ($orders as $order)
                        @include('includes.order', ['order' => $order])
                    @empty
                        <p>
                            {{__('main.nothing_found')}}
                        </p>
                    @endforelse
                </div>
                {!! $orders->appends(request()->except('page'))->render() !!}
            </div>
        </div>
    </div>

@endsection
