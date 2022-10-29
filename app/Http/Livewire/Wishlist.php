<?php

namespace App\Http\Livewire;

use App\Models\Lot;
use Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class Wishlist extends Component
{
    public string $instance = 'wishlist';
    public int $count = 0;

    protected $listeners = [
        'add',
        'remove',
    ];

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
            $previewUrl = $lot->getPreview();
            Cart::instance($this->instance);
            Cart::add($lot, 1, [
                'previewUrl' => $previewUrl,
            ]);
            $this->dispatchBrowserEvent('item-added', ['instance' => $this->instance]);
            $this->emit('updateWishlist');
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
            $this->emit('updateWishlist');
        }
    }

    public function render()
    {
        if(auth()->check()) {
            Cart::instance($this->instance);
            $this->restore();

            $this->count = Cart::count();

            $this->store();
        }

        return view('livewire.wishlist');
    }
}
