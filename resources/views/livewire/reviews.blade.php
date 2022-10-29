<div>
    <div class="row">
        <div class="col-12" id="reviews">
            <h3 class="single-lot-title" style="display: inline-block">
                {{__('main.reviews')}} <small>({{$reviews->total()}})</small>
            </h3>
            <button class="webz_btn add_review" modal-wrapper="#add-review">{{__('main.make_review')}}</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="reviews-sorter">
                <small>{{__('main.sort_by')}}:</small>
                <div class="reviews-sorter-item {{$sortBy === 'rating' ? 'active' : ''}}" wire:click="sort('rating')">
                    {{__('main.sort_rating')}}
                    @if($sortBy === 'rating')
                        @include('includes.svg', ['name' => $sortOrder === 'desc' ? 'sort-arrow' : 'sort-arrow-up'])
                    @endif
                </div>
                <div class="reviews-sorter-item  {{$sortBy === 'created_at' ? 'active' : ''}}" wire:click="sort('created_at')">
                    {{__('main.sort_date')}}
                    @if($sortBy === 'created_at')
                        @include('includes.svg', ['name' => $sortOrder === 'desc' ? 'sort-arrow' : 'sort-arrow-up'])
                    @endif
                </div>
                <div class="reviews-sorter-item  {{$sortBy === 'likes_count' ? 'active' : ''}}" wire:click="sort('likes_count')">
                    {{__('main.sort_likes')}}
                    @if($sortBy === 'likes_count')
                        @include('includes.svg', ['name' => $sortOrder === 'desc' ? 'sort-arrow' : 'sort-arrow-up'])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="reviews">
                @forelse ($reviews as $review)
                    @include('includes.review', ['review' => $review])
                @empty
                    {{__('main.no_reviews')}}
                @endforelse
                {{$reviews->links()}}
            </div>
        </div>
    </div>
</div>
