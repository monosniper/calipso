<div class="header_basket {{$count ? 'noties' : ''}}" id="{{$instance}}"  data-noties="{{$count}}">
    <i class="fas fa-{{$icon}}" wire:click="$emitSelf('toggle')"></i>
    <div class="basket-popup {{$isActive ? 'active' : ''}}">
        @unless($showAuthMessage)
            <div class="basket-popup-items">
                @forelse($items as $item)
                    <div class="basket-popup-item">
                        <div class="basket-popup-item-left">
                            <img src="{{$item->options->previewUrl}}" alt="{{$item->name}}">
                        </div>
                        <div class="basket-popup-item-right">
                            <div class="basket-popup-item-right-header">{{$item->options->shortTitle}}</div>
                            <div class="basket-popup-item-right-footer">
                                <div class="basket-popup-item-price">${{$item->price}}</div>
                                <div class="basket-popup-item-actions">
                                    <div class="basket-popup-item-counter">
                                        <div class="basket-popup-item-counter-decrement" wire:click="$emitSelf('decrement', '{{$item->rowId}}')">-</div>
                                        <div class="basket-popup-item-counter-quantity">{{$item->qty}}</div>
                                        <div class="basket-popup-item-counter-increment" wire:click="$emitSelf('increment', '{{$item->rowId}}')">+</div>
                                    </div>
                                    <div class="basket-popup-item-remove" wire:click="$emitSelf('remove', 'basket', '{{$item->rowId}}')"><i class="fas fa-times"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty

                @endforelse
            </div>
            <div class="basket-popup-total">{{__('main.total')}}: ${{$total}}</div>
            @if($total !== '0.00')
                <a href="{{route('dashboard.basket')}}" class="basket-popup-btn webz_btn">{{__('main.go_pay')}}</a>
            @else
                <div class="basket-popup-btn disable webz_btn">{{__('main.go_pay')}}</div>
            @endif
        @else
            {{__('errors.login_or_reg')}}
        @endunless
    </div>
</div>
