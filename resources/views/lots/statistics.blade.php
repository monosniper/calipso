@extends('layouts.main')

@section('title')
    {{__('main.statistics').' '.$lot->title}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/statistics.css') }}">
@endsection

@section('js')
    <script>

    </script>
@endsection

@section('content')

    <div class="container main">
        <div class="row">
            <div class="col-12">
                @include('includes.breadcrumbs', ['back_btn' => true, 'items' => [
                        [
                            'link' => route('dashboard.cabinet'),
                            'title' => __('main.profile'),
                        ],
                        [
                            'link' => route('dashboard.lots'),
                            'title' => __('main.my_lots'),
                        ],
                        [
                            'link' => route('lots.show', $lot->slug),
                            'title' => $lot->title,
                        ],
                        [
                            'link' => '#',
                            'title' => __('main.statistics'),
                        ],
                    ]
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="white_block stats-header" style="margin-top: 20px;">
                    <h3>
                        {{__('main.statistics')}}
                        {{$lot->title}}
                    </h3>
                    <span class="stats-badge {{$lot->status}}">{{__('main.statuses.'.$lot->status)}}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-6 col-sm-12">
                <div class="white_block stats-card">
                    <div class="stats-card-left">
                        <div class="stats-card-header">
                            {{__('stats.total_income')}}
                        </div>
                        <div class="stats-card-body">
                            ${{$total_income}}
                        </div>
                    </div>
                    <div class="stats-card-right">
                        <div class="stats-card-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-12">
                <div class="white_block stats-card">
                    <div class="stats-card-left">
                        <div class="stats-card-header">
                            {{__('stats.rating')}}
                        </div>
                        <div class="stats-card-body">
                            {{$rating}}
                        </div>
                    </div>
                    <div class="stats-card-right">
                        <div class="stats-card-icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-12">
                <div class="white_block stats-card">
                    <div class="stats-card-left">
                        <div class="stats-card-header">
                            {{__('stats.position')}}
                        </div>
                        <div class="stats-card-body">
                            {{$lot_position}}
                        </div>
                    </div>
                    <div class="stats-card-right">
                        <div class="stats-card-icon">
                            <i class="fas fa-map-pin"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-12">
                <div class="white_block stats-card">
                    <div class="stats-card-left">
                        <div class="stats-card-header">
                            {{__('stats.views')}}
                        </div>
                        <div class="stats-card-body">
                            {{$lot->views}}
                        </div>
                    </div>
                    <div class="stats-card-right">
                        <div class="stats-card-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <div class="white_block stats-block">
                    <h4 class="stats-block-title">{{__('stats.customers')}}</h4>
                    <div class="stats-customers">
                        @forelse($lot->purchases as $purchase)
                            <div class="stats-row">
                                <div class="stats-row-left">
                                    <div class="stats-buyer-user">
                                        <div class="stats-buyer-user-avatar" style="background-image: url({{ $purchase->user->getAvatar() }})"></div>
                                        <div class="stats-buyer-user-name">{{$purchase->user->fullName}}</div>
                                    </div>
                                </div>
                                <div class="stats-row-right stats-buyer-right">
                                    <div class="stats-buyer-date">{{$purchase->created_at->diffForHumans()}}</div>
                                    @if($purchase->lot->hasReviewFrom($purchase->user_id))
                                        <div class="stats-buyer-rating">
                                            @include('includes.rating', ['rating' => $purchase->lot->getReviewFrom($purchase->user_id)->rating])
                                        </div>
                                    @endif
                                    <a href="{{route('chat.add.conversation', $purchase->user_id)}}" class="stats-chat-btn">
                                        <i class="fas fa-comment"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p>{{__('stats.no_purchases')}}</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                @unless($lot->isPremium)
                    <div class="white_block flex stats-block">
                        <div class="stats-block-left">
                            <h4 class="stats-block-title">{{__('stats.premium')}}</h4>
                            <div class="stats-block-body">
                                {{$lot_position_premium}} <span class="stats-block-body-plus"><i class="fas fa-angle-up"></i> {{$lot_position - $lot_position_premium}}</span>
                            </div>
                            <div class="stats-block-footer">
                                <button class="webz_btn">{{__('stats.make_premium')}}</button>
                            </div>
                        </div>
                        <div class="stats-block-right">
                            <div class="stats-block-icon">
                                <i class="fas fa-map-pin"></i>
                            </div>
                        </div>
                    </div>
                @endunless
            </div>
        </div>

    </div>

@endsection
