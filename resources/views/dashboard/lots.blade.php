@extends('layouts.dashboard')

@section('title')
    {{__('main.my_lots')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.my_lots')}} <small>({{$user->lots_count}})</small></div>

        <div class="lots">
            @forelse($lots as $lot)
                @include('includes.lot', ['lot' => $lot, 'for' => 'my_lots', 'vertical' => true])
            @empty
                {{__('dashboard.no_lots')}}
            @endforelse
        </div>

        {{$lots->links()}}
    </div>
@endsection
