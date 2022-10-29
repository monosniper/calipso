<?php

namespace App\Http\Livewire;

use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Reviews extends Component
{
    public string $reviewable_type;
    public int $reviewable_id;
    protected LengthAwarePaginator $reviews;
    public string $sortBy = 'created_at';
    public string $sortOrder = 'desc';

    protected $listeners = ['toggle'];

    public function toggle(Review $review) {
        $review->liked() ? $review->unlike() : $review->like();
    }

    public function toggleSortOrder() {
        $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
    }

    public function sort($by) {
        if($this->sortBy === $by) $this->toggleSortOrder();
        $this->sortBy = $by;
    }

    public function open_sign_modal() {
        $this->dispatchBrowserEvent('modal_called_#sign');
    }

    public function render()
    {
        $this->reviews = Review::getFor($this->reviewable_type, $this->reviewable_id)
            ->with('likeCounter', 'user')
            ->withCount('likes')
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(12);

        return view('livewire.reviews', ['reviews' => $this->reviews]);
    }
}
