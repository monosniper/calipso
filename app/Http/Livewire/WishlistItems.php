<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class WishlistItems extends Component
{
    protected Collection $items;

    protected $listeners = ['updateWishlist' => 'update'];

    public function update() {
        \Cart::instance('wishlist')->restore(auth()->id());
        $this->items = \Cart::content();
    }

    public function mount() {
        \Cart::instance('wishlist')->restore(auth()->id());
        $this->items = \Cart::content();
    }

    public function render()
    {
        return view('livewire.wishlist-items', ['items' => $this->items]);
    }
}
