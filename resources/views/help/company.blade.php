@extends('layouts.main')

@section('title')
    {{__('help.company')}}
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
                            'title' => __('help.company'),
                        ],
            ]
        ])

        <h1 class="help-title">{{__('help.company')}}</h1>

        <div class="help-content">
            @for($i=0;$i<=10;$i++)
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae blanditiis esse ipsa mollitia nobis non pariatur qui repellat rerum. Aperiam aspernatur eius harum laboriosam magni minima nobis tempore tenetur vitae.</p>
            @endfor
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/help.css') }}"/>
@endsection
