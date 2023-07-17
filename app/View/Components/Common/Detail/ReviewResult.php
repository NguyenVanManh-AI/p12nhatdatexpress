<?php

namespace App\View\Components\Common\Detail;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ReviewResult extends Component
{
    public $item;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $rating_avg = round($this->item->ratings->pluck('star')->avg());
        $total_rating = $this->item->ratings->count();

        $user = Auth::guard('user')->user();

        $ratings = $this->item->ratings();
        if ($user) {
            $rating = $ratings->firstWhere('user_id', $user->id);
        } else {
            $rating = $ratings->whereNull('user_id')
                ->firstWhere('ip', request()->ip());
        }

        return view('components.common.detail.review-result', [
            'rating_avg' => $rating_avg,
            'total_rating' => $total_rating,
            'old_rating' => data_get($rating, 'star', 0)
        ]);
    }
}
