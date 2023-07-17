<?php

namespace App\Http\Requests\User\Personal;

use App\Http\Requests\BaseRequest;
use App\Rules\CheckBlockedKeyWord;
use App\Rules\CheckBlockedUser;
use App\Rules\CheckUserLevel;

class AddCommentRequest extends BaseRequest
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

    public function rules()
    {
        // except / #@ <> ; = |
        $pattern = '/^[a-zA-Z!$%^&*()?\-_\[\]\',.{}\":~+ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s+]+$/';

        return [
            'content_comment' => [
                new CheckUserLevel(2, 'bình luận'),
                new CheckBlockedUser('bình luận'),
                'required',
                'max:200',
                'regex:' . $pattern,
                new CheckBlockedKeyWord(),
                // maybe should check limit per day for comment
            ]
        ];
    }

    public function messages()
    {
        $messages = config('constants.validate_message', []);

        $messages['regex'] = ':attribute chỉ bao gồm chữ';

        return $messages;
    }
}
