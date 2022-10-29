@extends('layouts.dashboard')

@section('title')
    {{__('main.archive')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.archive')}}</div>

        <div class="lots">
            @forelse($lot_purchases as $purchase)
                @include('includes.lot', ['lot' => $purchase->lot])
            @empty
                {{__('dashboard.no_buyed_lots')}}
            @endforelse
        </div>

        {{$lot_purchases->links()}}
    </div>
@endsection
