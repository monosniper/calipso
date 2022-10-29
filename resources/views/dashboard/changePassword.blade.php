@extends('layouts.dashboard')

@section('title')
    {{__('main.change_password')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.change_password')}}</div>

        <form action="{{route('dashboard.change-password')}}" method="post" class="profile_form">
            @csrf

            <label class="profile_form_label" for="old_password">{{__('main.old_password')}}:</label>
            <input class="profile_form_field {{$errors->has('old_password') ? 'error' : ''}}" name="old_password" id="old_password" type="password" required>
            @if($errors->has('old_password'))
                @foreach($errors->get('old_password') as $err)
                    <div class="profile_form_field_error">{{$err}}</div>
                @endforeach
            @endif

            <label class="profile_form_label" for="new_password">{{__('main.new_password')}}:</label>
            <input class="profile_form_field {{$errors->has('new_password') ? 'error' : ''}}" name="new_password" id="new_password" type="password" required>
            @if($errors->has('new_password'))
                @foreach($errors->get('new_password') as $err)
                    <div class="profile_form_field_error">{{$err}}</div>
                @endforeach
            @endif

            <label class="profile_form_label" for="new_password_confirmation">{{__('main.new_password_again')}}:</label>
            <input class="profile_form_field {{$errors->has('new_password.confirmation') ? 'error' : ''}}" name="new_password_confirmation" id="new_password_confirmation" type="password" required>

            <button type="submit" class="webz_btn d-inline-block">{{__('main.update_password')}}</button>
        </form>
    </div>
@endsection
