@extends('layouts.dashboard')

@section('title')
    {{__('main.edit_profile')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.edit_profile')}}</div>

        <form action="{{route('forms.profile')}}" method="post" class="profile_form">
            @csrf

            <label class="profile_form_label" for="first_name">{{__('modals.profile.first_name')}}:</label>
            <input class="profile_form_field {{$errors->has('first_name') ? 'error' : ''}}" name="first_name"value="{{$user->first_name}}" id="first_name" type="text">
            @if($errors->has('first_name'))
                @foreach($errors->get('first_name') as $err)
                    <div class="profile_form_field_error">{{$err}}</div>
                @endforeach
            @endif

            <label class="profile_form_label" for="last_name">{{__('modals.profile.last_name')}}:</label>
            <input class="profile_form_field {{$errors->has('last_name') ? 'error' : ''}}" name="last_name" value="{{$user->last_name}}"id="last_name" type="text">
            @if($errors->has('last_name'))
                @foreach($errors->get('last_name') as $err)
                    <div class="profile_form_field_error">{{$err}}</div>
                @endforeach
            @endif

            <label class="profile_form_label" for="username">{{__('modals.profile.username')}}:</label>
            <input class="profile_form_field {{$errors->has('username') ? 'error' : ''}}" name="username" value="{{$user->username}}"id="username" type="text" required>
            @if($errors->has('username'))
                @foreach($errors->get('username') as $err)
                    <div class="profile_form_field_error">{{$err}}</div>
                @endforeach
            @endif

            <label class="profile_form_label" for="email">{{__('modals.profile.email')}}:</label>
            <input class="profile_form_field {{$errors->has('email') ? 'error' : ''}}" name="email"value="{{$user->email}}" id="email" type="email" required>
            @if($errors->has('email'))
                @foreach($errors->get('email') as $err)
                    <div class="profile_form_field_error">{{$err}}</div>
                @endforeach
            @endif

            <label class="profile_form_label" for="location">{{__('modals.profile.location')}}:</label>
            <input class="profile_form_field {{$errors->has('location') ? 'error' : ''}}" value="{{$user->location}}" name="location" id="location" type="text">
            @if($errors->has('location'))
                @foreach($errors->get('location') as $err)
                    <div class="profile_form_field_error">{{$err}}</div>
                @endforeach
            @endif

            @if(auth()->user()->isFreelancer)
                <label class="profile_form_label" for="location">{{__('modals.profile.categories')}}:</label>
                <select multiple name="categories[]" id="categories" class="modal_field">
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" {{in_array($category->id, $user->categories->pluck('id')->toArray()) ? 'selected' : ''}}>{{$category->name}}</option>
                    @endforeach
                </select>
            @endif

            <button type="submit" class="webz_btn d-inline-block">{{__('main.ready')}}</button>
        </form>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap3.min.css" integrity="sha512-MNbWZRRuTPBahfBZBeihNr9vTJJnggW3yw+/wC3Ev1w6Z8ioesQYMS1MtlHgjSOEKBpIlx43GeyLM2QGSIzBDg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $("#categories").selectize({
            delimiter: ",",
            maxItems: 2,
        });

        ymaps.ready(init);

        function init() {
            // Создаем выпадающую панель с поисковыми подсказками и прикрепляем ее к HTML-элементу по его id.
            const suggestView1 = new ymaps.SuggestView('location');
        }
    </script>
@endsection
