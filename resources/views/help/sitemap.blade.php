@extends('layouts.main')

@section('title')
    {{__('help.sitemap')}}
@endsection

@section('content')

    <div class="container main">
        @include('includes.breadcrumbs', ['items' => [
                                                               [
                                                                   'link' => route('home'),
                                                                   'title' => __('main.main'),
                                                               ],
                                                       [
                                                                   'link' => route('help.index'),
                                                                   'title' => __('main.help'),
                                                               ],
                                                               [
                                                                   'link' => '#',
                                                                   'title' => __('help.sitemap'),
                                                               ],
                                                   ]
                                               ])

        <h1 class="help-title">{{__('help.sitemap')}}</h1>

        <div class="help-content">
            <p>sitemap</p>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/help.css') }}"/>
@endsection
