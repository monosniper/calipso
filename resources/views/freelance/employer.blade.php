@extends('layouts.employer')

@section('title')
    {{$user->fullName}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_info_wrapper">
            <div class="profile_info_content">
                <div class="profile_title no_underline {{$user->username !== $user->fullName ? 'profile_has_username' : ''}}">{{ $user->fullName }}</div>
                @if($user->username !== $user->fullName)
                    <div class="profile_username">{{ '@'.$user->username }}</div>
                @endif
                <div class="profile_details">
                    @if($user->location)
                        <div class="profile_detail">
                            <div class="profile_detail_icon">
                                @include('includes.svg', ['name' => 'location'])
                            </div>
                            <div class="profile_detail_name">
                                {{ $user->location }}
                            </div>
                        </div>
                    @endif
                    @online($user->id)
                        <div class="profile_detail">
                            <div class="profile_detail_icon">
                                @include('includes.svg', ['name' => 'online'])
                            </div>

                            <div class="profile_detail_name">
                                {{__('main.now_online')}}
                            </div>
                        </div>
                    @endonline
                    @if($user->birthday)
                        <div class="profile_detail">
                            <div class="profile_detail_icon">
                                @include('includes.svg', ['name' => 'birthday'])
                            </div>
                            <div class="profile_detail_name">
                                {{__('main.years', ['years' => 20])}}
                            </div>
                        </div>
                    @endif
                    <div class="profile_detail">
                        <div class="profile_detail_icon">
                            @include('includes.svg', ['name' => 'home'])
                        </div>
                        <div class="profile_detail_name">
                            {{__('main.on_service', ['time' => $user->getTimeOnService()])}}
                        </div>
                    </div>
                </div>
                @if($user->isFreelancer)
                    <a href="{{route('freelance.freelancer', $user->id)}}" class="webz_btn d-inline-block">{{__('main.freelancer_profile')}}</a>
                @endif
            </div>
            <div class="profile_info_avatar">
                <div class="profile_avatar">
                    <div class="profile_avatar_image" style="background-image: url('{{ $user->getAvatar() }}')"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-end">
        @if($user->id !== auth()->id())
            <a href="{{route('chat.add.conversation', $user->id)}}" class="profile_menu_item messages_btn">
                <div class="profile_menu_item_icon">
                    @include('includes.svg', ['name' => 'chat'])
                </div>
                <div class="profile_menu_item_title ">{{__('main.write')}}</div>
            </a>
        @else
            <div class="profile_menu_item messages_btn" style="opacity: 0;"></div>
        @endif
    </div>
    <div class="grey_block">
        <div class="section_header">
            <div class="profile_title profile_title_header">
                <span>{{__('main.reviews')}} <small>({{$user->reviews_count}})</small></span>
                @auth
                    @if(auth()->user()->can('create', [App\Models\Review::class, $user->id]))
                        <button class="webz_btn white bordered open_modal" modal-wrapper="#add-review">{{__('main.make_review')}}</button>
                    @endif
                @endauth
            </div>
        </div>

        <div class="portfolios_tags">
            <a class="portfolios_tag {{request()->filled('reviews') ?: 'active'}}" href="{{route('freelance.employer', $user->id)}}">{{__('main.all')}}</a>
            <a class="portfolios_tag {{request()->reviews === 'positive' ? 'active' : ''}}" href="{{route('freelance.employer', ['user' => $user->id, 'reviews' => 'positive'])}}">{{__('main.positive')}}</a>
            <a class="portfolios_tag {{request()->reviews === 'negative' ? 'active' : '' }}" href="{{route('freelance.employer', ['user' => $user->id, 'reviews' => 'negative'])}}">{{__('main.negative')}}</a>
        </div>

        <div class="reviews">
            @forelse($user->reviews as $review)
                @include('includes.review', ['review' => $review])
            @empty
                {{ __('dashboard.no_reviews_for', ['name' => $user->fullName]) }}
            @endforelse
        </div>
    </div>

    @include('includes.modals.review', ['reviewable_type' => \App\Models\User::class, 'reviewable_id' => $user->id])
@endsection
