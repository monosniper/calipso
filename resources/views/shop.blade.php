@extends('layouts.main')

@section('title')
{{__('main.shop')}}
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap3.min.css" integrity="sha512-MNbWZRRuTPBahfBZBeihNr9vTJJnggW3yw+/wC3Ev1w6Z8ioesQYMS1MtlHgjSOEKBpIlx43GeyLM2QGSIzBDg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/shop.css') }}"/>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
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
                            <a href="{{route('lots.create')}}" class="webz_btn white">{{__('main.make_lot')}}</a>
                        @endauth
                    </div>
                    <div class="col-lg-8 col-sm-12">
                        @include('includes.search', ['page' => 'lots.index'])
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-12">
                <div class="white_block sidebar">
                    <div class="sidebar-title">{{ count($breadcrumbs) > 2 ? $breadcrumbs[count($breadcrumbs)-1]['title'] : 'Категории' }}</div>
                    <div class="sidebar-items">
                        @foreach($categories as $category)
                            <a href="{{ route('lots.index', ['category' => $category->slug] + request()->except(['page']))  }}" class="sidebar-item {{ request('category') === $category->slug ? 'active' : ''  }}">
                                <div class="sidebar-item-name">{{$category->name}}</div>
                                <div class="sidebar-item-count">{{$category->getTotalCount('lots')}}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="white_block white_block_no_x_padding sidebar">
                    <div class="sidebar-title">{{__('main.filters.price')}}</div>
                    <form action="{{route('lots.index')}}" class="sidebar-form">
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
                <div class="white_block sort-panel">
                    <a class="sort-panel-item {{ request('sort') === 'views' ? 'active' : ''  }}" rel='nofollow' href="{{ route('lots.index', ['sort' => 'views', 'direction' => 'desc'] + request()->query())  }}">{{__('main.order_by.popular')}}</a>
                    <a class="sort-panel-item {{ request('sort') === 'created_at' ? 'active' : ''  }}" rel='nofollow' href="{{ route('lots.index', ['sort' => 'created_at'] + request()->query())  }}">{{__('main.order_by.newer')}}</a>
                    <a class="sort-panel-item {{ request('sort') === 'price' && request('direction') === 'asc' ? 'active' : ''  }}" rel='nofollow' href="{{ route('lots.index', ['sort' => 'price', 'direction' => 'asc'] + request()->query())  }}">{{__('main.order_by.cheaper')}}</a>
                    <a class="sort-panel-item {{ request('sort') === 'price' && request('direction') === 'desc' ? 'active' : ''  }}" rel='nofollow' href="{{ route('lots.index', ['sort' => 'price', 'direction' => 'desc'] + request()->query())  }}">{{__('main.order_by.richer')}}</a>
                </div>
                <div class="lots">
                    @forelse ($lots as $lot)
                        @include('includes.lot', ['lot' => $lot])
                    @empty
                    {{__('main.nothing_found')}}
                    @endforelse
                </div>
                {!! $lots->appends(request()->except('page'))->render() !!}
            </div>
        </div>
    </div>

@endsection
