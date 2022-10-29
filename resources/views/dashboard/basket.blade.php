@extends('layouts.dashboard')

@section('title')
    {{__('main.basket')}}
@endsection

@section('content')
    <div class="grey_block">
        <livewire:basket-items />
    </div>
@endsection
