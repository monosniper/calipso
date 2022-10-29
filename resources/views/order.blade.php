@extends('layouts.main')

@section('head')
    <meta property="og:title" content="{{$order->title}}"/>
    <meta property="og:description" content="{{$order->getDescription(200)}}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content= "{{route('orders.show', $order->id)}}" />
    <meta property="og:image" content= "{{ asset('assets/img/og_image.jpg') }}" />
@endsection

@section('title')
    {{$order->title}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/order.css') }}"/>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
    <script src="https://yastatic.net/share2/share.js" defer></script>
@endsection

@section('content')
    @include('includes.modals.report')
    <div class="container main">
        <div class="row">
            <div class="col-12">
                @include('includes.breadcrumbs', [
                    'back_btn' => true,
                    'items' => [
                        [
                            'link' => route('home'),
                            'title' => __('main.main'),
                        ],
                         [
                            'link' => route('orders.index'),
                            'title' => __('main.freelance'),
                        ],
                        [
                            'link' => '#',
                            'title' => $order->title,
                        ],
                    ]
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="white_block">
                    <div class="single-order-header">
                        <div class="single-order-header-left">
                            <div class="single-order-date">{{$order->created_at->diffForHumans()}}</div>
                            <div class="single-order-status {{$order->status}}">{{__('order.status.'.$order->status)}}</div>
                            @if($order->isUrgent)
                                <div class="single-order-status urgent">
                                    <i class="fas fa-bolt"></i>
                                    {{__('order.urgent')}}
                                </div>
                            @endif
                        </div>
                        <div class="single-order-header-right">
                            @if($order->isSafe)
                                <div class="single-order-safe" title="{{__('order.work_in_safe')}}">
                                    <i class="fas fa-piggy-bank"></i>
                                </div>
                            @endif
                            <div class="single-order-days">{{trans_choice('order.days_count', $order->days)}}</div>
                            <div class="single-order-price">${{$order->price}}</div>
                        </div>
                    </div>
                    <h2 class="single-order-title">{{ $order->title  }}</h2>
                    <div class="single-order-description">{!! $order->description !!}</div>

                    <div class="files">
                        @forelse($order->media as $file)
                            <a title="{{$file->file_name}}" download href="{{$file->getUrl()}}" class="file">
                                <i class="file-icon fas {{$order->getMimeTypeIcon($file->mime_type)}}"></i>
                                <span class="file-name">{{mb_strimwidth($file->file_name, 0, 14).'...'}}</span>
                                <span class="file-size">{{$file->human_readable_size}}</span>
                            </a>
                        @empty

                        @endforelse
                    </div>

                    <div class="single-order-footer">
                        <div class="single-order-footer-left">
                            <div class="single-order-details">
                                <div class="single-order-details-item">
                                    <i class="fas fa-eye"></i>
                                    {{$order->views}}
                                </div>
                            </div>
                            <div class="single-order-tags">
                                @forelse($order->tags as $tag)
                                    <a href="{{route('orders.index', ['tags' => $tag->name])}}" class="single-order-tag">#{{$tag->name}}</a>
                                @empty

                                @endforelse
                            </div>
                        </div>
                        <div class="single-order-footer-right">
                            <div class="single-order-btns">
                                <div class="dropdown">
                                    <div class="dropdown_toggler">
                                        <button class="single-order-btn">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <div class="dropdown_menu">
                                        <div class="dropdown_menu_item">
                                            <div
                                                class="ya-share2"
                                                data-curtain
                                                data-color-scheme="normal"
                                                data-services="vkontakte,facebook,odnoklassniki,telegram,twitter"
                                                data-description="{{$order->getDescription(500)}}"
                                                data-title="{{$order->title}}"
                                                data-lang="{{app()->getLocale()}}"
                                                data-size="l"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                                <button class="single-order-btn open_modal" modal-wrapper="#report">
                                    <i class="fas fa-flag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @auth
                    @if(auth()->user()->isFreelancer && !auth()->user()->hasOfferOf($order->id) && auth()->id() !== $order->user_id)
                        <div class="white_block">
                            <form action="{{route('offers.store')}}" method="post" class="form">
                                @csrf
                                <h4 class="form-title">{{__('order.your_offer')}}:</h4>
                                <div class="form-group">
                                    <div class="form-group-item">
                                        <label for="days" class="form-label">{{__('order.offer.days_count')}}:</label>
                                        <input required type="number" name="days" value="{{request()->old('days')}}" placeholder="3" class="form-field">
                                    </div>
                                    <div class="form-group-item">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12">
                                                <label for="price" class="form-label">{{__('order.offer.price')}} ($):</label>
                                                <input required type="number" name="price" value="{{request()->old('price')}}" placeholder="$0.00" class="form-field">
                                            </div>
                                            <div class="col-lg-6 col-sm-12">
                                                <input class="form-checkbox" id="isSafe" type="checkbox" name="isSafe">
                                                <label for="isSafe">{{__('order.work_in_safe')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label for="content" class="form-label">{{__('order.offer.text')}}:</label>
                                <textarea required minlength="100" class="form-field" name="content" rows="8" placeholder="{{__('order.offer.your_message')}}">{{request()->old('content')}}</textarea>
                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                <div class="form-footer">
                                    <button class="webz_btn">{{__('main.ready')}}</button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="offers">
                        @if($order->offer())
                            @include('includes.offer', ['offer' => $order->offer(), 'chose' => true, 'no_choose' => true])
                        @endif
                        @forelse($order->offers as $offer)
                            @if($order->offer())
                                @if($offer->id !== $order->offer()->id)
                                    @include('includes.offer', ['offer' => $offer, 'no_choose' => $order->offer()])
                                @endif
                            @else
                                @include('includes.offer', ['offer' => $offer, 'no_choose' => $order->offer()])
                            @endif
                        @empty
                            <div class="white_block" style="    text-align: center;">{{__("order.no_offers")}}</div>
                        @endforelse
                    </div>
                @else
                    <div class="white_block" style="    text-align: center;">{{__("order.login_to_offer")}}</div>
                @endauth
            </div>
            <div class="col-lg-4 col-sm-12">
                <div class="white_block">
                    <div class="single-order-author-avatar">
                        <a href="{{route('freelance.employer', $order->user_id)}}"><img src="{{$order->user->getAvatar()}}" alt="{{$order->user->fullName}}"></a>
                    </div>
                    <a href="{{route('freelance.employer', $order->user_id)}}" class="single-order-author-name">
                        {{$order->user->fullName}}
                        @if($order->user->isVerified())
                              <div class="single-order-author-verification-circle"></div>
                        @endif
                    </a>
                    <div class="single-order-author-title"></div>
                    <div class="single-order-author-title">{{__('order.client_statistics')}}</div>
                    <div class="single-order-author-statistic">
                        <div class="single-order-author-statistic-item">
                            <div class="single-order-author-statistic-item-left">{{__('order.statistics.completed_orders')}}</div>
                            <div class="single-order-author-statistic-item-right">{{$order->user->completed_orders_count}}</div>
                        </div>
                        <div class="single-order-author-statistic-item">
                            <div class="single-order-author-statistic-item-left">{{__('order.statistics.active_orders')}}</div>
                            <div class="single-order-author-statistic-item-right">{{$order->user->active_orders_count}}</div>
                        </div>
                        <div class="single-order-author-statistic-item">
                            <div class="single-order-author-statistic-item-left">{{__('order.statistics.freelancers_reviews')}}</div>
                            <div class="single-order-author-statistic-item-right user-reviews">
                                <a href="{{route('freelance.employer', ['user' => $order->user_id, 'tag' => 'positive'])}}" class="single-order-author-statistic-item user-like">
                                    <i class="fas fa-thumbs-up"></i> {{$order->user->positive_reviews_count}}
                                </a>
                                <a href="{{route('freelance.employer', ['user' => $order->user_id, 'tag' => 'negative'])}}" class="single-order-author-statistic-item user-dislike">
                                    <i class="fas fa-thumbs-down"></i> {{$order->user->negative_reviews_count}}
                                </a>
                            </div>
                        </div>
                        <div class="single-order-author-statistic-item">
                            <div class="single-order-author-statistic-item-left">{{__('order.statistics.registered')}}</div>
                            <div class="single-order-author-statistic-item-right">{{$order->user->created_at->diffForHumans()}}</div>
                        </div>
                        <div class="single-order-author-statistic-item">
                            <div class="single-order-author-statistic-item-left">{{__('order.statistics.last_was')}}</div>
                            <div class="single-order-author-statistic-item-right">{{$order->user->lastOnlineDate()}}</div>
                        </div>
                    </div>
                    <div class="single-order-author-title">{{__('order.statistics.client_contacts')}}</div>
                    <p>{{__('order.statistics.no_contacts')}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
