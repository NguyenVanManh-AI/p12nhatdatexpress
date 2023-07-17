<?php

namespace App\Rules;

use App\Helpers\Helper;
use Illuminate\Contracts\Validation\Rule;

class CheckBlockedKeyWord implements Rule
{
    private $forbiddenWords;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $this->forbiddenWords = (array) Helper::checkBlockedKeyword($value, 1);

        return $this->forbiddenWords ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute có chứa từ cấm: ' . implode(', ', $this->forbiddenWords);
    }
}
