@extends('layouts.freelancer')

@section('title')
{{__('main.orders')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{ __('dashboard.orders_for', ['name' => $user->fullName])}} <small>({{$orders->count()}})</small></div>

        <div class="orders">
            @forelse($orders as $order)
                @include('includes.order', ['order' => $order])
            @empty
                {{ __('dashboard.no_orders_for', ['name' => $user->fullName]) }}
            @endforelse
        </div>

        {{$orders->links()}}
    </div>
@endsection
