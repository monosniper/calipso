<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WishlistCount extends Component
{
    protected int $count = 0;

    protected $listeners = ['updateWishlist' => 'update'];

    public function mount() {
        $this->count = \Cart::instance('wishlist')->count();
    }

    public function update() {
        $this->count = \Cart::instance('wishlist')->count();
    }

    public function render()
    {
        return view('livewire.counter', ['count' => $this->count]);
    }
}
