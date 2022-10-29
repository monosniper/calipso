@extends('layouts.dashboard')

@section('title')
    {{__('main.withdraw')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title profile_title_header">
            <span>
                {{$companion['fullName']}}
                @online($companion->id)
                    <span class="user_online user_online--block"></span>
                @endonline
            </span>
            <a href="{{route('chat.index')}}" class="webz_btn white bordered">{{__('main.back_to_messages')}}</a>
        </div>
        <div class="chat-box">
            <div class="chat-box-messages">
                @forelse($conversation->messages as $message)
                    <div class="chat-box-message {{$message->sender->id === auth()->id() ? 'right' : 'left'}}">
                        <div class="chat-box-message-content">
                            <span>{{$message->body}}</span>
                            <span class="chat-box-message-date">{{$message->created_at->isoFormat('hh:mm, D MMMM')}}</span>
                        </div>
                    </div>
                @empty

                @endforelse
            </div>
            <div class="chat-box-footer">
                <form action="{{route('chat.add.message')}}" method="post">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{$conversation->id}}">
                    <input required type="text" name="message" class="chat-box-field">
                    <button class="chat-box-send"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
@endsection
