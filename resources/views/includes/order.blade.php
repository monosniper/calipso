<div class="order {{$order->isUrgent ? 'urgent' : ''}}">
    <div class="order-left">
        <a href="{{route('orders.show', $order->id)}}" class="order-title">{{$order->title}}</a>
        <div class="order-details">
            <div class="order-detail">
                <i class="fas fa-clock"></i>
                {{$order->created_at->diffForHumans()}}
            </div>
            <div class="order-detail">
                <i class="far fa-comment-dots"></i>
                {{trans_choice('order.offers_count', $order->offers_count)}}
            </div>
        </div>
    </div>
    <div class="order-right">

        @if($order->isSafe)
            @include('includes.tooltip', ['handler' => '
                <div class="order-safe">
                    <i class="fas fa-piggy-bank"></i>
                </div>
            ', 'message' => __('order.work_in_safe')])
        @endif

        @if($order->isUrgent)
            <div class="order-urgent">
                <i class="fas fa-bolt"></i>
                {{__('main.urgent')}}
            </div>
        @endif

        <div class="order-price">${{$order->price}}</div>

        @if(isset($for) && $for === 'my_orders')
            <span class="order-badge {{$order->status}}">{{__('order.status.'.$order->status)}}</span>
        @endif
    </div>
    <span class="order-footer">
        @if(isset($for) && $for === 'my_orders')
            <div>
                @if(auth()->user()->can('update', $order) && $order->status === \App\Models\Order::ACTIVE_STATUS)
                    <a href="{{route('orders.edit', $order->id)}}" class="webz_btn white bordered">{{__('main.edit')}}</a>
                @endif
                @if($order->isSafe && $order->status !== \App\Models\Order::ACTIVE_STATUS)
                    <a href="{{route('freelance.safe', $order->id)}}" class="webz_btn white bordered">{{__('order.safe')}}</a>
                @elseif($order->status !== \App\Models\Order::ACTIVE_STATUS)
                    @if($order->user_id === auth()->id())
                        <a href="{{route('chat.add.conversation', $order->freelancer_id)}}" class="webz_btn white bordered">{{__('order.freelancer_chat')}}</a>
                    @else
                        <a href="{{route('chat.add.conversation', $order->user_id)}}" class="webz_btn white bordered">{{__('order.employer_chat')}}</a>
                    @endif
                @endif
            </div>
        @else
            <a href="{{route('freelance.employer', $order->user->id)}}" class="order-author">
                <div class="order-author-avatar" style="background-image: url({{$order->user->getAvatar()}})"></div>
                <div class="order-author-name">{{$order->user->fullName}}</div>
            </a>
        @endif
        <div class="order-tags">
            @forelse ($order->tags as $tag)
                <a href="{{route('orders.index', ['tags' => $tag->name])}}" class="order-tag">#{{$tag->name}}</a>
            @empty

            @endforelse
        </div>
    </span>
</div>
