<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;
use App\Models\ProjectComment;
use App\Rules\CheckBlockedKeyWord;
use App\Rules\CheckBlockedUser;
use Illuminate\Support\Facades\Auth;

class UpdateCommentRequest extends BaseRequest
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
                new CheckBlockedUser('bình luận'),
                'required',
                'max:200',
                'regex:' . $pattern,
                new CheckBlockedKeyWord(),
                function ($attribute, $value, $fail) {
                    $user = Auth::guard('user')->user();

                    if (now()->startOfDay()->timestamp < $this->comment->updated_at && $this->comment->updated_at < now()->endOfDay()->timestamp) {
                        return $fail("Mỗi bình luận chỉ được chỉnh sửa 1 lần trên ngày");
                    }

                    $limitUpdatePerDay = config('constants.classified.comment.limit_update_per_day', 3);
                    $dayUpdates = ProjectComment::where('user_id', $user->id)
                        ->whereBetWeen('updated_at', [now()->startOfDay()->timestamp, now()->endOfDay()->timestamp])
                        ->count();

                    if ($dayUpdates >= $limitUpdatePerDay) return $fail("Mỗi tài khoản chỉ được chỉnh sửa {$limitUpdatePerDay} bình luận trên ngày");
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
