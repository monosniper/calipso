<div class="lot {{isset($vertical) ? 'vertical' : ''}}">
    <a href="{{route('lots.show', $lot->slug)}}" class="lot_img" style="background-image: url('{{ $lot->getPreview() }}')">
        <span class="term-container">
            @if($lot->isPremium)
                <span title="Премиум" class="term premium">
                    <i class="fas fa-star"></i>
                </span>
            @endif

            @if($lot->isTop())
                <span title="Топ в своей категории" class="term top">
                    <i class="fas fa-crown"></i>
                </span>
            @endif
        </span>
    </a>
    <div class="lot_content">
        <a href="{{route('lots.show', $lot->slug)}}" class="lot_title">
            <span>{{$lot->getShortTitle(50)}}</span>
            @if(isset($for) && $for === 'my_lots')
                <span class="lot-badge {{$lot->status}}">{{__('main.statuses.'.$lot->status)}}</span>
            @endif
        </a>
        <div class="lot_text">{!! $lot->getShortDescription(100) !!}</div>
        <div class="lot_footer">
            <div class="lot_btns">
                @if(isset($for) && $for === 'basket')
                    <div onclick="Livewire.emit('remove', 'basket', '{{$rowId}}');" class="lot_btn lot_basket basket_add active">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                @elseif(isset($for) && $for === 'wishlist')
                    <div onclick="Livewire.emit('remove', 'wishlist', '{{$rowId}}');" class="lot_btn lot_wishlist wishlist_add active">
                        <i class="fas fa-heart"></i>
                    </div>
                @elseif(isset($for) && $for === 'my_lots')
                    <a href="{{route('lots.statistics', $lot->slug)}}" class="webz_btn white bordered">{{__('main.statistics')}}</a>
                    @if($lot->status !== \App\Models\Lot::MODERATION_STATUS)
                        <a href="{{route('lots.edit', $lot->slug)}}" class="webz_btn white bordered">{{__('main.edit')}}</a>
                    @endif
                @else
                    <div onclick="Livewire.emit('add', 'basket', '{{$lot->slug}}')" class="lot_btn lot_basket basket_add">
                        <span class="material-icons-outlined">shopping_cart</span>
                    </div>
                    <div onclick="Livewire.emit('add', 'wishlist', '{{$lot->slug}}')" class="lot_btn lot_wishlist wishlist_add">
                        <span class="material-icons-outlined">favorite_border</span>
                    </div>
                @endif
            </div>
            @if($lot->discount_price)
                <div>
                    <div class="lot_price old">${{$lot->price}}</div>
                    <div class="lot_price">${{$lot->discount_price}}</div>
                </div>
            @else
                <div class="lot_price">${{$lot->price}}</div>
            @endif
        </div>
    </div>
</div>
