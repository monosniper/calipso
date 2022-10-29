<div class="lots">
    @forelse($items as $item)
        @include('includes.lot', ['lot' => $item->model, 'vertical' => true, 'for' => 'wishlist', 'rowId' => $item->rowId])
    @empty
        {{__('dashboard.no_wishlist_lots')}}
    @endforelse
</div>
