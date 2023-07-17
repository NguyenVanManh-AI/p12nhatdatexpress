<?php

namespace App\Models\Event;

use App\Traits\Models\AdminHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRating extends Model
{
    use HasFactory;
    use AdminHistoryTrait;

    protected $table = 'event_rating';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $fillable = [
        'event_id',
        'user_id',
        'ip',
        'rating_time',
        'star'
    ];

    public function get_rating() {
        if ($this->one_star == 1)
            return 1;
        else if ($this->two_star == 1)
            return 2;
        else if ($this->three_star == 1)
            return 3;
        else if ($this->four_star == 1)
            return 4;
        else if ($this->five_star == 1)
            return 5;
        else return 0;
    }
}