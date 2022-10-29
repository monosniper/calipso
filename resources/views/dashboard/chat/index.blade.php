@extends('layouts.dashboard')

@section('title')
    {{__('main.messages')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.messages')}}</div>
        <div class="conversations">
            @forelse ($conversations as $conversation)
                @include('includes.conversation', [
                    'conversation' => $conversation,
                ])
            @empty
                <p>
                    {{__('main.nothing_found')}}
                </p>
            @endforelse
        </div>

        {!! $conversations->links() !!}
    </div>
@endsection
