@extends('layouts.dashboard')

@section('title')
    {{__('main.my_orders')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.my_orders')}} <small>({{$orders->count()}})</small></div>
        <div class="orders">
            @forelse($orders as $order)
                @include('includes.order', ['order' => $order, 'for' => 'my_orders'])
            @empty
                {{__('dashboard.no_orders')}}
            @endforelse
        </div>

        {{$orders->links()}}
    </div>
@endsection
