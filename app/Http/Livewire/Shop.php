<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Shop extends Component
{

    public $categories;
    public $lots;

    public $sort;
    public $sortDirection;

    public $category;

    public function render()
    {
        return view('livewire.shop');
    }
}
