@extends('layouts.freelancer')

@section('title')
    {{$user->fullName}}
@endsection

@section('content')

    @include('includes.modals.make_portfolio')

    <div class="grey_block">
        <div class="profile_info_wrapper">
            <div class="profile_info_content">
                <div class="profile_title no_underline {{$user->username !== $user->fullName ? 'profile_has_username' : ''}}">{{ $user->fullName }}</div>
                <span class="profile_category">{{implode(' · ', $user->categories->pluck('name')->toArray())}}</span>
                @if($user->username !== $user->fullName)
                    <span class="profile_username"> · {{ '@'.$user->username }}</span>
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
                <a href="{{route('freelance.employer', $user->id)}}" class="webz_btn d-inline-block">{{__('main.employer_profile')}}</a>
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
        <div class="profile_title">{{__('main.resume')}}</div>
        <div class="profile_text">{!! $user->resume !!}</div>
    </div>
    <div class="grey_block">
        <div class="section_header">
            <div class="profile_title">{{__('main.portfolio')}}</div>
        </div>

        <div class="portfolios_tags">
            <a class="portfolios_tag {{request()->filled('tag') ?: 'active'}}" href="{{route('dashboard.cabinet')}}">{{__('main.all')}}</a>
            @foreach($user->portfolios->pluck('tag') as $tag)
                @if($tag)
                    <a href="{{route('freelance.freelancer', ['user' => $user->id, 'tag' => $tag])}}" class="portfolios_tag {{request('tag') !== $tag ?: 'active'}}">{{$tag}}</a>
                @endif
            @endforeach
        </div>

        <div class="portfolios">
            @foreach($user->portfolios as $portfolio)
                @include('includes.portfolio')
            @endforeach
        </div>
    </div>
@endsection
