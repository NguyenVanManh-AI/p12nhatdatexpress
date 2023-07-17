<?php

namespace App\Rules;

use App\Models\User\UserLevel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel implements Rule
{
    public $levelId;
    private $action;

    /**
     * Create a new rule instance.
     * @param $levelId = 1
     *
     * @return void
     */
    public function __construct($levelId = 1, $action = null)
    {
        $this->levelId = $levelId;
        $this->action = $action;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = Auth::guard('user')->user();

        return $user->user_level_id >= $this->levelId;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $level = UserLevel::find($this->levelId);

        return 'Cấp bậc tài khoản phải từ ' . data_get($level, 'level_name', 'mới') . ' trở lên mới có thể ' . $this->action;
    }
}
