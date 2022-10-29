<div>
    <div class="profile_title profile_title_header">
        <span>{{__('main.basket')}}</span>
        <div>
            <span class="total-sum">{{__('main.total_sum')}}: ${{$total_sum}}</span>
            <a href="{{route('cart.pay')}}" class="webz_btn white bordered">{{__('main.puy')}}</a>
        </div>
    </div>

    <div class="lots">
        @forelse($items as $item)
            @include('includes.lot', ['lot' => $item->model, 'vertical' => true, 'for' => 'basket', 'rowId' => $item->rowId])
        @empty
            {{__('dashboard.no_basket_lots')}}
        @endforelse
    </div>
</div>
