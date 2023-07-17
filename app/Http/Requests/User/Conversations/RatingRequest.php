<?php

namespace App\Http\Requests\User\Conversations;

use App\Http\Requests\BaseRequest;
use App\Rules\CheckBlockedUser;

class RatingRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rating' => [
                new CheckBlockedUser('đánh giá'),
                'required',
                'numeric',
                'between:1,5'
            ]
        ];
    }
}
