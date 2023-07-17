<?php

namespace App\Http\Requests\Classified;

use App\Http\Requests\BaseRequest;
use App\Models\Classified\ClassifiedComment;
use App\Rules\CheckBlockedKeyWord;
use App\Rules\CheckBlockedUser;
use App\Rules\CheckUserLevel;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // except / #@ <> ; = |
        $pattern = '/^[a-zA-Z!$%^&*()?\-_\[\]\',.{}\":~+ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s+]+$/';

        return [
            'content' => [
                new CheckUserLevel(2, 'bình luận'),
                new CheckBlockedUser('bình luận'),
                'required',
                'max:200',
                'regex:' . $pattern,
                new CheckBlockedKeyWord(),
                function ($attribute, $value, $fail) {
                    $user = Auth::guard('user')->user();
                    // người đăng có thể bình luận không giới hạn cho tin của mình
                    if ($user->id == $this->classified->user_id) return;

                    $duplicate = ClassifiedComment::where('user_id', $user->id)
                        ->showed()
                        ->firstWhere('classified_id', $this->classified->id);
                    if ($duplicate) return $fail('Mỗi tài khoản chỉ được đăng 1 bình luận trên 1 bài đăng');

                    $maxCommentsPerDay = config('constants.classified.comment.limit_per_day', 5);
                    // số lượng comments vào bài viết của người khác trong ngày
                    $dayComments = ClassifiedComment::select('classified_comment.*')
                        ->leftJoin('classified', 'classified.id', '=', 'classified_comment.classified_id')
                        ->where('classified_comment.user_id', $user->id)
                        ->where('classified.user_id', '!=', $user->id)
                        ->whereBetWeen('classified_comment.created_at', [now()->startOfDay()->timestamp, now()->endOfDay()->timestamp])
                        ->count();
                    if ($dayComments >= $maxCommentsPerDay) return $fail("Mỗi tài khoản chỉ được đăng {$maxCommentsPerDay} bình luận trên ngày");
                },
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
