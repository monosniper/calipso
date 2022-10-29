@extends('layouts.freelancer')

@section('title')
    {{__('main.reviews')}}
@endsection

@section('content')
    @include('includes.modals.review', ['reviewable_type' => \App\Models\User::class, 'reviewable_id' => $user->id])

    <div class="grey_block">
        <div class="profile_title profile_title_header">
            <span>{{ __('dashboard.reviews_for', ['name' => $user->fullName]) }} <small>({{$reviews->count()}})</small></span>
            @auth
                @if(auth()->user()->can('create', [App\Models\Review::class, $user->id]))
                    <button class="webz_btn white bordered open_modal" modal-wrapper="#add-review">{{__('main.make_review')}}</button>
                @endif
            @endauth
        </div>

        <div class="portfolios_tags">
            <a class="portfolios_tag {{request()->filled('tag') ?: 'active'}}" href="{{route('freelance.freelancer.reviews', $user->id)}}">{{__('main.all')}}</a>
            <a class="portfolios_tag {{request()->tag === 'positive' ? 'active' : ''}}" href="{{route('freelance.freelancer.reviews', ['user' => $user->id, 'tag' => 'positive'])}}">{{__('main.positive')}}</a>
            <a class="portfolios_tag {{request()->tag === 'negative' ? 'active' : '' }}" href="{{route('freelance.freelancer.reviews', ['user' => $user->id, 'tag' => 'negative'])}}">{{__('main.negative')}}</a>
        </div>

        <div class="reviews">
            @forelse($reviews as $review)
                @include('includes.review', ['review' => $review])
            @empty
                {{ __('dashboard.no_reviews_for', ['name' => $user->fullName]) }}
            @endforelse
        </div>

        {{$reviews->links()}}
    </div>
@endsection
