<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class Rating extends Component
{
    public $rate;
    public $ip;

    public $one_star;
    public $two_star;
    public $three_star;
    public $four_star;
    public $five_star;
    public $avg_star;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($rate, $ip = null)
    {
        $this->rate = $rate;
        $this->ip = $ip;

        $this->caculator_star();
        $this->caculator_avg_star();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.rating');
    }

    /**
     * Caculator Start
     * @return void
     */
    public function caculator_star(){
        $this->one_star = $this->rate->where('one_star', 1)->count();
        $this->two_star = $this->rate->where('two_star', 1)->count();
        $this->three_star = $this->rate->where('three_star', 1)->count();
        $this->four_star = $this->rate->where('four_star', 1)->count();
        $this->five_star = $this->rate->where('five_star', 1)->count();
    }

    /**
     * Caculator AVG star
     * @return void
     */
    public function caculator_avg_star(){
        if($this->rate->count() > 0) {
            $result = (1 * $this->one_star + 2 * $this->two_star + 3 * $this->three_star + 4 * $this->four_star + 5 * $this->five_star) / $this->rate->count();
            $this->avg_star = number_format($result, 0);
        }else{
            $this->avg_star = 0;
        }
    }
}
