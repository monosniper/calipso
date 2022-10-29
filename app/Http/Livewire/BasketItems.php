<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Cart;

class BasketItems extends Component
{
    protected Collection $items;
    public $total_sum;

    protected $listeners = ['updateBasket' => 'update'];

    public function update() {
        \Cart::instance('basket')->restore(auth()->id());
        $this->total_sum = Cart::instance('basket')->subtotal();
        $this->items = Cart::content();
    }

    public function mount() {
        $this->total_sum = Cart::instance('basket')->subtotal();
        \Cart::instance('basket')->restore(auth()->id());
        $this->items = Cart::content();
    }

    public function render()
    {
        return view('livewire.basket-items', ['items' => $this->items]);
    }
}
