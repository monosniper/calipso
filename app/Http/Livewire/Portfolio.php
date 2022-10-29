<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Portfolio extends Component
{
    public function like() {
        $this->portfolio->like();
    }

    public function render()
    {
        return view('livewire.portfolio');
    }
}
