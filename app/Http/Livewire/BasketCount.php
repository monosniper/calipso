<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BasketCount extends Component
{
    protected int $count = 0;

    protected $listeners = ['updateBasket' => 'update'];

    public function mount() {
        $this->count = \Cart::instance('basket')->count();
    }

    public function update() {
        $this->count = \Cart::instance('basket')->count();
    }

    public function render()
    {
        return view('livewire.counter', ['count' => $this->count]);
    }
}
