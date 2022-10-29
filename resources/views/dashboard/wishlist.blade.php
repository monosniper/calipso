@extends('layouts.dashboard')

@section('title')
    {{__('main.wishlist')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.wishlist')}}</div>

        <livewire:wishlist-items />
    </div>
@endsection
