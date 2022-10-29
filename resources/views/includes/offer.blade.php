<div class="offer white_block {{isset($bordered) && $bordered ? 'bordered' : ''}} {{isset($chose) && $chose ? 'chose' : ''}}">
    @if(isset($chose) && $chose)
        <span class="chose-header">{{__('order.chose_offer')}}</span>
    @endif
    <div class="offer-header">
        <div class="offer-header-left">
            <a href="{{route('freelance.freelancer', $offer->user->id)}}">
                <img src="{{$offer->user->getAvatar()}}" class="offer-avatar" />
            </a>
            <div class="offer-author-info">
                <a href="{{route('freelance.freelancer', $offer->user->id)}}" class="offer-author-name">{{$offer->user->username}}</a>
                <div class="offer-author-details">
                    <div class="single-order-author-statistic-item-right user-reviews">
                        <a href="{{route('freelance.freelancer.reviews', ['user' => $offer->user_id, 'tag' => 'positive'])}}" class="single-order-author-statistic-item user-like">
                            <i class="fas fa-thumbs-up"></i> {{$offer->user->positive_reviews_count}}
                        </a>
                        <a href="{{route('freelance.freelancer.reviews', ['user' => $offer->user_id, 'tag' => 'negative'])}}" class="single-order-author-statistic-item user-dislike">
                            <i class="fas fa-thumbs-down"></i> {{$offer->user->negative_reviews_count}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="offer-header-right">
            @if($offer->isSafe)
                <div class="offer-badge offer-safe" title="{{__('order.work_in_safe')}}">
                    <i class="fas fa-piggy-bank"></i>
                </div>
            @endif
            <div class="offer-badge offer-days">{{trans_choice('order.days_count', $offer->days)}}</div>
            <div class="offer-badge offer-price">${{$offer->price}}</div>
        </div>
    </div>
    <div class="offer-body">{{$offer->content}}</div>
    <div class="offer-footer">
        @unless(isset($no_choose) && $no_choose)
            @if($offer->order->user_id === auth()->id())
                <button class="webz_btn white bordered open_modal" modal-wrapper="#choose-freelancer-{{$offer->id}}">{{__('main.choose_freelancer')}}</button>
            @endif
        @endunless

        <div class="offer-date">{{$offer->created_at->diffForHumans()}}</div>
    </div>
    @unless(isset($no_modal))
        @include('includes.modals.choose_freelancer', ['offer' => $offer])
    @endunless
</div>
