<div title="{{ !request()->has('category') && $freelancer->isTop() ? __('main.best_on_site') : $freelancer->fullName }}" class="freelancer white_block {{ !request()->has('category') && $freelancer->isTop() ? 'top' : '' }}">
    <div class="freelancer-header">
        <div class="freelancer-header-left">
            <a href="{{route('freelance.freelancer', $freelancer->id)}}" class="freelancer-avatar">
                @online($freelancer->id)
                <span class="user_online"></span>
                @endonline
                <img src="{{$freelancer->getAvatar()}}" alt="{{$freelancer->fullName}}">
                @if($freelancer->isTop(request()->input('category') ? request()->input('category') : false))
                    <span title="{{__('main.best_in_category')}}" class="freelancer-crown">
                        @include('includes.svg', ['name' => 'crown'])
                    </span>
                @endif
            </a>
        </div>
        <div class="freelancer-header-right">
            <div class="freelancer-header-right-left">
                <a href="{{route('freelance.freelancer', $freelancer->id)}}" class="freelancer-name">{{$freelancer->fullName}}</a>
                @forelse($freelancer->categories as $category)
                    <div class="freelancer-category">{{$category->name}}</div>
                @empty

                @endforelse
                <div class="freelancer-location">{{$freelancer->location}}</div>
            </div>
            <div class="freelancer-header-right-right">
                <div class="freelancer-rating">
                    <i class="fas fa-star"></i>
                    {{$freelancer->rating}}
                </div>
                <div class="freelancer-statistic">
                    <a href="{{route('freelance.freelancer.reviews', ['user' => $freelancer->id, 'tag' => 'positive'])}}" class="freelancer-statistic-item freelancer-statistic-item-like">
                        <i class="fas fa-thumbs-up"></i> {{$freelancer->positive_reviews_count}}
                    </a>
                    <a href="{{route('freelance.freelancer.reviews', ['user' => $freelancer->id, 'tag' => 'negative'])}}" class="freelancer-statistic-item freelancer-statistic-item-dislike">
                        <i class="fas fa-thumbs-down"></i> {{$freelancer->negative_reviews_count}}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="freelancer-body">{!! $freelancer->getShortResume() !!}</div>
</div>
