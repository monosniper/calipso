@extends('layouts.employer')

@section('title')
{{__('main.lots')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{ __('dashboard.lots_for', ['name' => $user->fullName]) }} <small>({{$lots->count()}})</small></div>

        <div class="lots">
            @forelse($lots as $lot)
                @include('includes.lot', ['lot' => $lot])
            @empty
                {{ __('dashboard.no_lots_for', ['name' => $user->fullName]) }}
            @endforelse
        </div>

        {{$lots->links()}}
    </div>
@endsection
