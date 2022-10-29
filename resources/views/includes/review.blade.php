<div class="review">
    <div class="review-left">
        <div class="review-avatar">
            <img src="{{$review->user->getAvatar()}}" alt="{{$review->user->fullName}}">
            <span class="review-country flag-icon flag-icon-{{ $review->user->country_code }}"></span>
        </div>
    </div>
    <div class="review-right">
        <div class="review-header">
            <div class="review-header-left">
                <div>
                    <span class="review-name">{{$review->user->fullName}}</span>
                    <span class="review-time">{{$review->createdAt}}</span>
                </div>
                <span class="review-rating">
                    @include('includes.rating', ['rating' => $review->rating])
                </span>
            </div>
            <span class="review-header-right">
                <span class="review-like" wire:click="{{ auth()->check() ? auth()->id() !== $review->user->id ? 'toggle('.$review->id.')' : '' : 'open_sign_modal'}}">
                    @include('includes.svg', ['name' => $review->liked() ? "review-like-active" : "review-like"])
                    {{$review->likeCounter ? $review->likeCounter->count : 0}}
                </span>
            </span>
        </div>
        <div class="review-body">
            <div class="review-title">{{$review->title}}</div>
            <div class="review-content">{{$review->content}}</div>
        </div>
{{--        @auth--}}
{{--            <div class="review-footer">--}}
{{--                <span class="review-action review-reply">Ответить</span>--}}
{{--                <span class="review-action review-report">Пожаловаться</span>--}}
{{--            </div>--}}
{{--        @endauth--}}
    </div>
    <div class="children"></div>
</div>
