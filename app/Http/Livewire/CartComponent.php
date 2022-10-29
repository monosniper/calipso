<?php

namespace App\Http\Livewire;

use App\Models\Lot;
use Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class CartComponent extends Component
{
    public string $icon = '';
    public string $instance = 'basket';
    public bool $isActive = false;
    public string $total = '0.00';
    public int $count = 0;
    public bool $showAuthMessage = false;
    protected Collection $items;

    protected $listeners = [
        'toggle',
        'hide',
        'increment',
        'decrement',
        'add',
        'remove',
    ];

    public function toggle() {
        $this->isActive = !$this->isActive;
    }

    public function hide() {
        $this->isActive = false;
    }

    public function store() {
        try {
            Cart::instance($this->instance)->store(auth()->id());
        } catch (\Exception $exception) {

        }
    }

    public function restore() {
        try {
            Cart::instance($this->instance)->restore(auth()->id());
        } catch (\Exception $exception) {

        }
    }

    public function add($instance, Lot $lot) {
        if($instance === $this->instance) {
            $shortTitle = $lot->getShortTitle(20);
            $previewUrl = $lot->getPreview();
            Cart::instance($this->instance);

            if($lot->user_id !== auth()->id()) {
                try {
                    Cart::add($lot, 1, [
                        'shortTitle' => $shortTitle,
                        'previewUrl' => $previewUrl,
                    ]);
                } catch (\Exception $err) {

                }

                $this->dispatchBrowserEvent('item-added', ['instance' => $this->instance]);
                $this->emit('updateBasket');
            }
        }
    }

    public function remove($instance, $rowId) {
        if($instance === $this->instance) {
            Cart::instance($this->instance);
            try {
                Cart::remove($rowId);
            } catch (\Exception $err) {

            }
            $this->store();
            $this->emit('updateBasket');
        }
    }

    public function increment($rowId) {
        Cart::instance($this->instance);
        Cart::update($rowId, $this->getQuantity($rowId) + 1);
        $this->store();
    }

    public function decrement($rowId) {
        Cart::instance($this->instance);
        Cart::update($rowId, $this->getQuantity($rowId) - 1);
        $this->store();
    }

    public function getQuantity($rowId): int {
        Cart::instance($this->instance);
        return Cart::get($rowId)->qty;
    }

    public function render()
    {
        if(auth()->check()) {
            Cart::instance($this->instance);
            $this->restore();

            $this->items = Cart::content();

            $this->total = Cart::subtotal();
            $this->count = Cart::count();

            $this->store();
        } else {
            $this->items = Collection::make([]);
            $this->showAuthMessage = true;
        }

        return view('livewire.cart', [
            'items' => $this->items
        ]);
    }
}
