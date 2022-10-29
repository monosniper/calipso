<div class="freelancer white_block">
    <div class="freelancer-header">
        <div class="freelancer-header-left">
            <a href="{{route('chat.conversation', $conversation['id'])}}" class="freelancer-avatar">
                @online($conversation['companion']->id)
                <span class="user_online"></span>
                @endonline
                <img src="{{$conversation['companion']->getAvatar()}}" alt="{{$conversation['companion']->fullName}}">
            </a>
        </div>
        <div class="freelancer-header-right">
            <div class="freelancer-header-right-left">
                <a href="{{route('chat.conversation', $conversation['id'])}}" class="freelancer-name">{{$conversation['companion']['fullName']}}</a>
                @if($conversation['last_message'])
                    <div class="freelancer-location">{{$conversation['last_message']['created_at_human']}}: {{ $conversation['last_message']['body'] }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
