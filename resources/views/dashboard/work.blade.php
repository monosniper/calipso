@extends('layouts.dashboard')

@section('title')
    {{__('main.work')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.work')}} <small>({{$user->orders_work_count}})</small></div>
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
